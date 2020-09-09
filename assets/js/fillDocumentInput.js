const documentInput = document.getElementById('document_documentFile');

documentInput.addEventListener('change',function (e) {
    const fileName = documentInput.files[0].name;
    const nextSibling = e.target.nextElementSibling;
    nextSibling.innerText = fileName;
});
