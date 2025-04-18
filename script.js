document.addEventListener("DOMContentLoaded", function() {
    fetchStudents();

    document.getElementById("formulaire").addEventListener("submit", function(e) {
        e.preventDefault();
        var formData = new FormData(this);

        fetch("enregistrer.php", {
            method: "POST",
            body: formData
        })
        .then(res => res.text())
        .then(data => {
            if (data === "updated") {
                alert("Étudiant modifié !");
            } else {
                alert("Étudiant ajouté !");
            }
            this.reset();
            document.getElementById("id").value = "";
            fetchStudents();
        });
    });
});

function fetchStudents() {
    fetch("enregistrer.php")
        .then(res => res.text())
        .then(data => {
            document.querySelector("#studentTable tbody").innerHTML = data;
        });
}

function editStudent(id) {
    fetch(`enregistrer.php?edit=${id}`)
        .then(res => res.json())
        .then(data => {
            document.getElementById("id").value = data.id;
            document.getElementById("nomuser").value = data.nomuser;
            document.getElementById("prenomuser").value = data.prenomuser;
            document.getElementById("emailuser").value = data.emailuser;
            document.getElementById("age").value = data.age;
        });
}

function deleteStudent(id) {
    if (confirm("Supprimer cet étudiant ?")) {
        fetch(`enregistrer.php?delete=${id}`)
            .then(res => res.text())
            .then(data => {
                alert("Étudiant supprimé !");
                fetchStudents();
            });
    }
}