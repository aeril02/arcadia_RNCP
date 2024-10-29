// header pour les page du site 
// creation d'un array pour la creation du menu
const menuItems = [
    {href: "index.php", label: "Accueil"},
    {href: "service.php", label: "Services"},
    {href: "habitats.php", label: "Habitats"},
    {href: "contact.php", label: "Contact"},
]
// recuperer le chemin actuel
const currentPath = window.location.pathname;

// creation de la boucle for pour l'affichage du menu 
const menu = document.getElementById("header");

// rajout du logo 
const logo = document.createElement("img");
// attention le lien doit etre fait en fonction de index.php par de Header_Footer.js
logo.src ="../doc/photo/image_site/logo_arcadia_WEBP.webp";
logo.alt ="logo_du_site";
logo.classList.add("logoArcadia");
menu.appendChild(logo);


menuItems.forEach(item => {
    const li = document.createElement("li");
    li.classList.add("navHeader");
    const a = document.createElement("a");
    a.classList.add("texteHeader");
    a.href = item.href;
    a.textContent = item.label;

    // test pour savoir si le lien est actif ou non
    if (currentPath.includes(item.href.split("/").pop())) {
        a.classList.add("active");
    }

    li.appendChild(a);
    menu.appendChild(li);
});

//footer pour les pages du site 
// creation d'un array pour la creation du menu
const footerItems = [
    {href: "../../page/legal.html", label: "Mentions legales"},
    {href: "../../page/billeterie.php", label: "Billetterie"},
    {href: "../../page/reglement.php", label: "Réglement interne"},
    {href: "../../page/recrutement.html", label: "Recrutement"},
    {href: "../../page/login.php", label: "Connexion"},
]

// creation de la boucle for pour l'affichage du menu
const footer = document.getElementById("footer");
footerItems.forEach(item => {
    const li = document.createElement("li");
    li.classList.add("navFooter");
    const a = document.createElement("a");
    a.classList.add("texteFooter");
    a.href = item.href;
    a.textContent = item.label;
    li.appendChild(a);
    footer.appendChild(li);
});