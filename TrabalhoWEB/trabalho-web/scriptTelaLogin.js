function telaHome(){
    const email = document.getElementById("email").value;
    const senha = document.getElementById("senha").value;

    if ( email === "thiago@email.com" && senha === "1"){
        window.location.href = "/Home/telaHome.html";
        return false;
    }else{
        alert("Usuario ou senha invalidos!");
        return false;
    }
}