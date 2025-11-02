<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

require 'config.php';

// 1. Get API key from request
$api_key = $_GET['api_key'] ?? '';
if (!$api_key) {
    echo json_encode(['error' => 'API key required']);
    exit;
}

// 2. Get website_id from api_keys table
$stmt = $conn->prepare("SELECT website_id FROM api_keys WHERE api_key = ?");
$stmt->bind_param("s", $api_key);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    echo json_encode(['error' => 'Invalid API key']);
    exit;
}

$website_id = $res->fetch_assoc()['website_id'];

// 3. Fetch social links for this website
$sql = "
    SELECT sp.name, sp.icon_class, wsl.url 
    FROM website_social_links wsl
    JOIN social_platforms sp ON wsl.platform_id = sp.id
    WHERE wsl.website_id = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $website_id);
$stmt->execute();
$social_result = $stmt->get_result();

$social_links = [];
while ($row = $social_result->fetch_assoc()) {
    $social_links[] = [
        'platform' => $row['name'],
        'icon'     => $row['icon_class'],
        'url'      => $row['url']
    ];
}

// 4. Fetch published news for this website (with tag names and type)
$sql = "
    SELECT 
        n.id, n.unique_news_id, n.title, n.slug, n.content,
        n.location, n.location, n.latitude, n.longitude,
         n.highlights, n.points, n.notes,
        n.tag_id, n.media, n.news_date, n.created_at,
        c.name AS category_name,
        n.device_id,
        n.position_id,
        n.type_id,
        t.name AS type_name
    FROM news n
    LEFT JOIN categories c ON n.category_id = c.id
    LEFT JOIN types t ON n.type_id = t.id
    WHERE JSON_CONTAINS(n.website_id, JSON_QUOTE(?))
      AND n.status = 'published'
    ORDER BY n.news_date DESC
";

$stmt = $conn->prepare($sql);
$website_id_str = (string)$website_id;
$stmt->bind_param("s", $website_id_str);
$stmt->execute();
$news_result = $stmt->get_result();

// 🔁 Fetch all tags once for ID-to-name mapping
$tagMap = [];
$tagStmt = $conn->prepare("SELECT id, name FROM tags");
$tagStmt->execute();
$tagResult = $tagStmt->get_result();
while ($tagRow = $tagResult->fetch_assoc()) {
    $tagMap[$tagRow['id']] = $tagRow['name'];
}

$positionMap = [];
$positionStmt = $conn->prepare("SELECT id, name FROM positions");
$positionStmt->execute();
$positionResult = $positionStmt->get_result();
while ($positionRow = $positionResult->fetch_assoc()) {
    $positionMap[$positionRow['id']] = $positionRow['name'];
}

$deviceMap = [];
$deviceStmt = $conn->prepare("SELECT id, name FROM devices");
$deviceStmt->execute();
$deviceResult = $deviceStmt->get_result();
while ($deviceRow = $deviceResult->fetch_assoc()) {
    $deviceMap[$deviceRow['id']] = $deviceRow['name'];
}

// 🔁 Loop through news rows and format output
// 🔁 Loop through news rows and format output
$news = [];
while ($row = $news_result->fetch_assoc()) {
    // ✅ Handle media field
    $mediaRaw = json_decode($row['media'] ?? '', true);
    $row['media'] = [];

    if (is_array($mediaRaw)) {
        // ✅ Case 1: structured object (new format)
        if (isset($mediaRaw['type']) && isset($mediaRaw['files'])) {
            foreach ($mediaRaw['files'] as $filename) {
                $url = "http://localhost/news-portal/uploads/news_media/" . $filename;
                $row['media'][] = [
                    'type' => strtolower($mediaRaw['type']),
                    'url'  => $url
                ];
            }
        } else {
            // ✅ Case 2: fallback if it's a simple array of strings
            foreach ($mediaRaw as $filename) {
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $type = 'image';
                if (in_array(strtolower($ext), ['mp4', 'webm'])) $type = 'video';
                if (strpos($filename, 'youtube.com') !== false || strpos($filename, 'youtu.be') !== false) $type = 'youtube';

                $url = "http://localhost/news-portal/uploads/news_media/" . $filename;
                $row['media'][] = [
                    'type' => $type,
                    'url'  => $url
                ];
            }
        }
    }

    // ✅ Points field
    $row['points'] = json_decode($row['points'] ?? '[]');

    // ✅ Tag mapping
    $tagIds = json_decode($row['tag_id'] ?? '[]');
    $row['tags'] = [];
    if (is_array($tagIds)) {
        foreach ($tagIds as $tid) {
            if (isset($tagMap[$tid])) {
                $row['tags'][] = ['id' => $tid, 'name' => $tagMap[$tid]];
            }
        }
    }
    unset($row['tag_id']);

    // ✅ Position mapping
    $positionIds = json_decode($row['position_id'] ?? '[]');
    $row['positions'] = [];
    if (is_array($positionIds)) {
        foreach ($positionIds as $pid) {
            if (isset($positionMap[$pid])) {
                $row['positions'][] = ['id' => $pid, 'name' => $positionMap[$pid]];
            }
        }
    }
    unset($row['position_id']);

    // ✅ Device mapping
    $deviceIds = json_decode($row['device_id'] ?? '[]');
    $row['devices'] = [];
    if (is_array($deviceIds)) {
        foreach ($deviceIds as $did) {
            if (isset($deviceMap[$did])) {
                $row['devices'][] = ['id' => $did, 'name' => $deviceMap[$did]];
            }
        }
    }
    unset($row['device_id']);

    $news[] = $row;
}
// Filter and group news by position name
$sliderPositions = ['first', 'second', 'third', 'fourth'];
$positionedNews = [
    'first' => null,
    'second' => null,
    'third' => null,
    'fourth' => null
];

foreach ($news as $item) {
    foreach ($item['positions'] as $pos) {
        $posName = strtolower($pos['name']);
        if (in_array($posName, $sliderPositions) && !$positionedNews[$posName]) {
            $positionedNews[$posName] = $item;
        }
    }
}



$sql = "SELECT id, name, designation, photo, created_at 
        FROM teams 
        WHERE JSON_CONTAINS(website_id, JSON_QUOTE(?)) 
          AND status = 'active' 
        ORDER BY created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $website_id_str);
$stmt->execute();
$result = $stmt->get_result();

$teams = [];
while ($row = $result->fetch_assoc()) {
    $row['photo'] = "http://localhost/news-portal/uploads/teams/" . $row['photo'];
    $teams[] = $row;
}
$sql = "SELECT id, name, logo, website, created_at 
        FROM sponsors 
        WHERE JSON_CONTAINS(website_id, JSON_QUOTE(?)) 
          AND status = 'active' 
        ORDER BY created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $website_id_str);
$stmt->execute();
$result = $stmt->get_result();

$sponsors = [];
while ($row = $result->fetch_assoc()) {
    $row['logo'] = "http://localhost/news-portal/uploads/sponsors/" . $row['logo'];
    $sponsors[] = $row;
}

$sql = "SELECT id, name, designation, message, image, created_at 
        FROM testimonials 
        WHERE JSON_CONTAINS(website_id, JSON_QUOTE(?)) 
          AND status = 'active' 
        ORDER BY created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $website_id_str);
$stmt->execute();
$result = $stmt->get_result();

$testimonials = [];
while ($row = $result->fetch_assoc()) {
    $row['image'] = "http://localhost/news-portal/uploads/testimonials/" . $row['image'];
    $testimonials[] = $row;
}

$sql = "SELECT id, title, description, event_date, location, image, created_at 
        FROM events 
        WHERE JSON_CONTAINS(website_id, JSON_QUOTE(?)) 
        ORDER BY created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $website_id_str);
$stmt->execute();
$result = $stmt->get_result();

$events = [];
while ($row = $result->fetch_assoc()) {
    $row['image'] = "http://localhost/news-portal/uploads/events/" . $row['image'];
    $events[] = $row;
}

$sql = "SELECT id, title, description, image, created_at 
        FROM programs 
        WHERE JSON_CONTAINS(website_ids, JSON_QUOTE(?)) 
          AND status = 'active' 
        ORDER BY created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $website_id_str);
$stmt->execute();
$result = $stmt->get_result();

$programs = [];
while ($row = $result->fetch_assoc()) {
    $row['image'] = "http://localhost/news-portal/uploads/programs/" . $row['image'];
    $programs[] = $row;
}


$sql = "SELECT id, title, description, images, created_at 
        FROM gallery 
        WHERE JSON_CONTAINS(website_id, JSON_QUOTE(?)) 
          AND status = 'active' 
        ORDER BY created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $website_id_str);
$stmt->execute();
$result = $stmt->get_result();

$gallery = [];
while ($row = $result->fetch_assoc()) {
    $imageList = json_decode($row['images'], true) ?? [];
    foreach ($imageList as &$img) {
        $img = "http://localhost/news-portal/uploads/gallery/" . $img;
    }
    $row['images'] = $imageList;
    $gallery[] = $row;
}


// Fetch positions for mapping
$positionMap = [];
$positionStmt = $conn->prepare("SELECT id, name FROM positions");
$positionStmt->execute();
$positionResult = $positionStmt->get_result();
while ($positionRow = $positionResult->fetch_assoc()) {
    $positionMap[$positionRow['id']] = $positionRow['name'];
}

$sql = "SELECT id, title, media_path, link, position_id, ad_type, youtube_url, external_url, created_at 
        FROM advertisements 
        WHERE JSON_CONTAINS(website_id, JSON_QUOTE(?)) 
          AND status = 'active' 
        ORDER BY created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $website_id_str);
$stmt->execute();
$result = $stmt->get_result();

$ads = [];
while ($row = $result->fetch_assoc()) {
    // Determine media_url based on ad_type
    switch ($row['ad_type']) {
        case 'image':
        case 'gif':
        case 'video':
            $row['media_url'] = "http://localhost/news-portal/uploads/ads/" . $row['media_path'];
            break;
        case 'youtube':
            $row['media_url'] = $row['youtube_url'];
            break;
        case 'external':
            $row['media_url'] = $row['external_url'];
            break;
        default:
            $row['media_url'] = null;
    }

    // Parse position_id JSON and convert to position names
    $positionIds = json_decode($row['position_id'] ?? '[]');
    $row['positions'] = [];
    if (is_array($positionIds)) {
        foreach ($positionIds as $pid) {
            if (isset($positionMap[$pid])) {
                $row['positions'][] = ['id' => $pid, 'name' => $positionMap[$pid]];
            }
        }
    }

    // Clean up unnecessary fields
    unset($row['media_path'], $row['youtube_url'], $row['external_url'], $row['position_id']);

    $ads[] = $row;
}



$sql = "SELECT id, text, created_at 
        FROM scroller 
        WHERE JSON_CONTAINS(website_id, JSON_QUOTE(?)) 
          AND status = 'active' 
        ORDER BY created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $website_id_str);
$stmt->execute();
$result = $stmt->get_result();

$scrollers = [];
while ($row = $result->fetch_assoc()) {
    $scrollers[] = $row;
}


$sql = "SELECT id, organization_name, title, description, apply_link, logo, created_at 
        FROM scholarship 
        WHERE JSON_CONTAINS(website_id, JSON_QUOTE(?)) 
          AND status = 'active' 
        ORDER BY created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $website_id_str);
$stmt->execute();
$result = $stmt->get_result();

$scholarships = [];
while ($row = $result->fetch_assoc()) {
    $row['logo'] = "http://localhost/news-portal/uploads/scholarships/" . $row['logo'];
    $scholarships[] = $row;
}


$sql = "SELECT id, title, description, price, stock, image, created_at 
        FROM products 
        WHERE JSON_CONTAINS(tag_ids, tag_ids) OR 1=1 -- optional tag filtering
        ORDER BY created_at DESC";

$stmt = $conn->prepare("SELECT id, title, description, price, stock, image, created_at 
        FROM products 
        WHERE status = 'active' 
        ORDER BY created_at DESC");

$stmt->execute();
$result = $stmt->get_result();

$products = [];
while ($row = $result->fetch_assoc()) {
    $row['image'] = "http://localhost/news-portal/uploads/products/" . $row['image'];
    $products[] = $row;
}


$sql= "SELECT * from tags";
$stmt = $conn->prepare($sql);
$stmt->execute();
$res = $stmt->get_result();
$tags = [];
while ($row = $res->fetch_assoc()) {
    $tags[] = [
        'id'   => $row['id'],
        'name' => $row['name']
        
    ];
}

$sql = "SELECT * FROM categories";
$stmt = $conn->prepare($sql);
$stmt->execute();
$res = $stmt->get_result();
$categories = [];
while ($row = $res->fetch_assoc()) {
    $categories[] = [
        'id'   => $row['id'],
        'name' => $row['name']
        
    ];
}

// 7. Send final JSON response
echo json_encode([
    'status'        => 'success',
    'website_id'    => $website_id,
    'social_links'  => $social_links ?? [],
    'news_count'    => count($news ?? []),
    'news'          => $news ?? [],
    'slider_news' => $positionedNews, // 👈 your filtered result
    'tags'          => $tags ?? [],
    'categories'    => $categories ?? [],
    'teams'         => $teams ?? [],
    'sponsors'      => $sponsors ?? [],
    'testimonials'  => $testimonials ?? [],
    'events'        => $events ?? [],
    'programs'      => $programs ?? [],
    'gallery'       => $gallery ?? [],
    'advertisements'=> $ads ?? [],
    'scrollers'     => $scrollers ?? [],
    'scholarships'  => $scholarships ?? [],
    'products'      => $products ?? []
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

?>
