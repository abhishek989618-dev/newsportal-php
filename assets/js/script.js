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