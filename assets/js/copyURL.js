const copyButtons = document.getElementsByClassName('copy-button');
const copyText = document.getElementsByClassName('copy-text');

for (let i = 0; i < copyButtons.length; i++) {
    copyButtons[i].addEventListener('click', function () {
        copyText[i].select();
        document.execCommand('copy');
        alert('URL copiée !');
    });
}
