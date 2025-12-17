// Validation formulaires
function validateForm(form){
    let email = form.querySelector("input[name='email']").value;
    let password = form.querySelector("input[name='mot_de_passe']").value;
    let tel = form.querySelector("input[name='telephone']")?.value || "";

    let emailRegex = /^\S+@\S+\.\S+$/;
    let telRegex = /^\d{8,15}$/;
    let passwordRegex = /^.{6,}$/;

    if(!emailRegex.test(email)){
        alert("Email invalide");
        return false;
    }
    if(tel && !telRegex.test(tel)){
        alert("Téléphone invalide");
        return false;
    }
    if(!passwordRegex.test(password)){
        alert("Mot de passe doit faire 6 caractères minimum");
        return false;
    }
    return true;
}

// Modals dynamiques
function openModal(id){
    document.getElementById(id).classList.add('active');
}
function closeModal(id){
    document.getElementById(id).classList.remove('active');
}

// SweetAlert simple
function confirmAction(message, callback){
    if(confirm(message)){
        callback();
    }
}

// Calendrier interactif simple
function selectDate(dateElem){
    let selected = document.querySelector('.calendar-day.selected');
    if(selected) selected.classList.remove('selected');
    dateElem.classList.add('selected');
}
