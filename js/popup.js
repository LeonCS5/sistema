function showPopup(message, type) {
  document.addEventListener('DOMContentLoaded', function () {
    let popup = document.getElementById('popup');
    popup.textContent = message; 
    popup.classList.remove('hidden');
    console.log("Ola")
    // Timeout para transição animada
    setTimeout(() => {
        popup.classList.add(type);
        popup.classList.add('show');
    }, 10);
    setTimeout(hidePopup, 4000,[type])
  });

}

function hidePopup(type) {
  console.log("Teste")
  let popup = document.getElementById('popup');
  popup.classList.remove('show');
  popup.classList.remove(type);
  // Aguarda a transição terminar antes de esconder
  setTimeout(() => {
    popup.classList.add('hidden');
  }, 300);
}