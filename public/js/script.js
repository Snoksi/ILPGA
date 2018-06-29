
// Pop up
$(document).ready(function () {
    $(".btnPop").click(function (e) {
        e.preventDefault();
        $(".pop, .pop-background").fadeIn(300);
    });

    $(".pop .popClose").click(function () {
        $(".pop, .pop-background").fadeOut(300);
    });

    $("#createFolder").on('submit', function (e) {
        console.log('lol');
        e.preventDefault();

        var name = $("#createFolder input[name='name']").val();

        $.ajax({
            url: "/api/tests/create_folder/"+name+"/",
            type: "POST"
        }).done(function() {
            location.reload();
        });
    });
});








var dragSrcEl = null;

function handleDragStart(e) {
    // Target (this) element is the source node.
    dragSrcEl = this;

    e.dataTransfer.effectAllowed = 'move';
    e.dataTransfer.setData('text/html', this.outerHTML);

    this.classList.add('dragElem');
}
function handleDragOver(e) {
    if (e.preventDefault) {
        e.preventDefault(); // Necessary. Allows us to drop.
    }
    this.classList.add('over');

    e.dataTransfer.dropEffect = 'move';  // See the section on the DataTransfer object.

    return false;
}

function handleDragEnter(e) {
    // this / e.target is the current hover target.
}

function handleDragLeave(e) {
    this.classList.remove('over');  // this / e.target is previous target element.
}

function handleDrop(e) {
    // this/e.target is current target element.

    if (e.stopPropagation) {
        e.stopPropagation(); // Stops some browsers from redirecting.
    }

    // Don't do anything if dropping the same column we're dragging.
    if (dragSrcEl != this) {
        // Set the source column's HTML to the HTML of the column we dropped on.
        //alert(this.outerHTML);
        //dragSrcEl.innerHTML = this.innerHTML;
        //this.innerHTML = e.dataTransfer.getData('text/html');
        this.parentNode.removeChild(dragSrcEl);
        var dropHTML = e.dataTransfer.getData('text/html');
        this.insertAdjacentHTML('beforebegin',dropHTML);
        var dropElem = this.previousSibling;
        addDnDHandlers(dropElem);

    }
    this.classList.remove('over');
    return false;



}

function handleDragEnd(e) {
    // this/e.target is the source node.
    this.classList.remove('over');

    /*[].forEach.call(cols, function (col) {
      col.classList.remove('over');
    });*/
}

function addDnDHandlers(elem) {
    elem.addEventListener('dragstart', handleDragStart, false);
    elem.addEventListener('dragenter', handleDragEnter, false)
    elem.addEventListener('dragover', handleDragOver, false);
    elem.addEventListener('dragleave', handleDragLeave, false);
    elem.addEventListener('drop', handleDrop, false);
    elem.addEventListener('dragend', handleDragEnd, false);

}

var cols = document.querySelectorAll('.liste article');
[].forEach.call(cols, addDnDHandlers);

var itemsDrag = [];

for(i = 0; i < $("section[data-folder-id]").length; i++){
    itemsDrag += $("body").data("folder-id");
    console.log($("body").data("folder-id"));
};



dragula([

])

    .on('drag', function(el) {

    })
    .on('dragend', function(el) {

    });







//balise fonction lightbox.

/*la premier partie exprime les actions du bouton choix multiple*/
document.addEventListener('DOMContentLoaded', function () {

        var light = document.querySelector('#choixm');
        var closeBtn = document.querySelector('.choixmultiple .boutonAnnuler');
        var lighty = document.querySelector('.choixmultiple');

        //rend visible et invisible la fenetre de connection
        light.addEventListener("click", function () {
            lighty.style.visibility = "visible";
        });

        closeBtn.addEventListener("click", function () {
            lighty.style.visibility = "hidden";
        });
    }
)

//la premier partie exprime les actions du bouton du champ de texte
document.addEventListener('DOMContentLoaded', function () {
        var light = document.querySelector('#champs');
        var closeBtn = document.querySelector('.réponseouverte .boutonAnnuler');
        var lighty = document.querySelector('.réponseouverte');

        //rend visible et invisible la fenetre de connection
        light.addEventListener("click", function () {
            lighty.style.visibility = "visible";
        });

        closeBtn.addEventListener("click", function () {
            lighty.style.visibility = "hidden";
        });
    }

)

//la premier partie exprime les actions du bouton du champ de texte
document.addEventListener('DOMContentLoaded', function () {
        var light = document.querySelector('#unique');
        var closeBtn = document.querySelector('.choixunique .boutonAnnuler');
        var lighty = document.querySelector('.choixunique');

        //rend visible et invisible la fenetre de connection
        light.addEventListener("click", function () {
            lighty.style.visibility = "visible";
        });

        closeBtn.addEventListener("click", function () {
            lighty.style.visibility = "hidden";
        });
    }

)

//la premier partie exprime les actions du bouton echelle
document.addEventListener('DOMContentLoaded', function () {
        var light = document.querySelector('#ech');
        var closeBtn = document.querySelector('.echelle .boutonAnnuler');
        var lighty = document.querySelector('.echelle');

        //rend visible et invisible la fenetre de connection
        light.addEventListener("click", function () {
            lighty.style.visibility = "visible";
        });

        closeBtn.addEventListener("click", function () {
            lighty.style.visibility = "hidden";
        });
    }

)

//balise fonction audio je joue tout le temps

var player = document.querySelector('#audio');


var nbrEcoutes = 0;


function play(idPlayer, control, nbr) {
    nbrEcoutes++;
    if(nbr >= nbrEcoutes) {
        var player = document.querySelector('#' + idPlayer);

        if (player.paused) {
            player.play();
            control.textContent = 'Play';
        } else {
            player.pause();
            control.textContent = 'Pause';
        }
    }

}

function resume(idPlayer) {
    var player = document.querySelector('#' + idPlayer);

    player.currentTime = 0;
    player.pause();
}


