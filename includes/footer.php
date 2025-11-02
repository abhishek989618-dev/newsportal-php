<script>
  function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('active');
  }

  function previewImage(event) {
    const preview = document.getElementById('preview');
    const reader = new FileReader();
    reader.onload = function () {
      preview.style.backgroundImage = `url(${reader.result})`;
      preview.innerHTML = '';
    };
    reader.readAsDataURL(event.target.files[0]);
  }
</script>

<script>
const newsChart = new Chart(document.getElementById('newsChart'), {
  type: 'pie',
  data: {
    labels: <?= json_encode(array_keys($newsStats)) ?>,
    datasets: [{
      label: 'News Status',
      data: <?= json_encode(array_values($newsStats)) ?>,
      backgroundColor: ['#4caf50', '#ff9800', '#f44336'],
    }]
  },
  options: {
    responsive: true,
    plugins: {
      title: {
        display: true,
        text: 'News by Status'
      }
    }
  }
});

const userChart = new Chart(document.getElementById('userChart'), {
  type: 'bar',
  data: {
    labels: <?= json_encode(array_column($userRoleStats, 'role')) ?>,
    datasets: [{
      label: 'Users per Role',
      data: <?= json_encode(array_column($userRoleStats, 'total')) ?>,
      backgroundColor: '#2196f3'
    }]
  },
  options: {
    responsive: true,
    plugins: {
      title: {
        display: true,
        text: 'User Roles Distribution'
      }
    },
    scales: {
      y: { beginAtZero: true }
    }
  }
});
</script>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>