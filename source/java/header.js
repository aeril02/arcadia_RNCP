// header pour les page du site 
// creation d'un array pour la creation du menu
const menuItems = [
    {href: "../../page/index.php", label: "Accueil"},
    {href: "../../page/service.php", label: "Services"},
    {href: "../../page/habitats.php", label: "Habitats"},
    {href: "../../page/contact.php", label: "Contact"},
    {href: "../../page/login.php", label: "Connexion"},
]
// recuperer le chemin actuel
const currentPath = window.location.pathname;

// creation de la boucle for pour l'affichage du menu 
const menu = document.getElementById("menu");
menuItems.forEach(item => {
    const li = document.createElement("li");
    const a = document.createElement("a");
    a.href = item.href;
    a.textContent = item.label;

    // test pour savoir si le lien est actif ou non
    if (currentPath.includes(item.href.split("/").pop())) {
        a.classList.add("active");
    }

    li.appendChild(a);
    menu.appendChild(li);
});