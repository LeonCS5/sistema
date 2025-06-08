document.addEventListener('DOMContentLoaded', function () {
     let seletor = document.getElementById('seletorCor');
     seletor.addEventListener('input', function () {
         // Atualiza a variável CSS com a cor escolhida
             document.documentElement.style.setProperty('--primary-1', this.value);
         });


    if (localStorage.getItem("theme") === "dark") {
      document.body.classList.add("dark");
      
    }

    
    
    let body = this.querySelector('body')
    let theme = document.getElementById("dark-theme")
    theme.addEventListener("click", ()=>{
    
            body.classList.toggle("dark")
            document.body.classList.add("transition-theme");
            let theme = document.body.classList.contains("dark") ? "dark" : "light";
            localStorage.setItem("theme", theme);
    })






});







