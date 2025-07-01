console.log("Account page script loaded.");

document.getElementById('profile_image').addEventListener('change', function(event) {
    const inp = event.target;
    if (inp.files && inp.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('imagePreview').src = e.target.result;
        };
        reader.readAsDataURL(inp.files[0]);
    }
});