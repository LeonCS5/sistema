let tamanhoFonte = 16;
let max_size = 23
let current_size = 16

let btn_cont = 0;

let btn_size = 32

let btn = document.getElementById("aumentar")


document.addEventListener('DOMContentLoaded', function () {
    if(localStorage.getItem("font") > 16){
        document.body.style.fontSize = localStorage.getItem("font") + 'px';
    
    }

    if(localStorage.getItem("btn-size") > 32){
        let btns = document.querySelectorAll('.btn');
        btns.forEach(btn => {
            btn.style.height = localStorage.getItem("btn-size") + 'px';
        });

    }

})

btn.addEventListener("click", ()=>{

    let btns = document.querySelectorAll('.btn');

    if(current_size < max_size){
        current_size += 1;
        btn_cont += 1;
        document.body.style.fontSize = current_size + 'px';
        localStorage.setItem("font", current_size);

        btns.forEach(btn => {
            btn.style.height = btn_size + btn_cont  + 'px'
        });
        localStorage.setItem("btn-size", btn_size + btn_cont);

    }else{
        current_size = 16;
        document.body.style.fontSize = tamanhoFonte + 'px';
        
        btn_cont = 0
        btns.forEach(btn => {
            btn.style.height = 32  + 'px';
        });

        localStorage.setItem("font", current_size);
        localStorage.setItem("btn-size", 32);


    }
})


