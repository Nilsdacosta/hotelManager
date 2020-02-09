$("#monBouton").click(function(){
    $.ajax({
        url: "https://api.chucknorris.io/jokes/random?category=movie",
        type : "GET",         // verbe http ( 5 differents les plus connus GET et POST)
        success : function(monTableau){
            $("#monNouveauParagraphe").html(monTableau.value);
        },
        error : function(){
            alert("raté !");
        },
    });
})