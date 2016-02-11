// Modal functions and error display
function entityError(title, msg) {
    $(".entity-error-body-content").html('<h1>'+title+'</h1><p>'+msg+'</p>');
    $(".entity-error-container").show();
}

$('#closeAlert').click(function() {
    $('.entity-error-container').hide();
});

function entityModal(html) {
    $("#entity-modal-body-content").html(html).attr('class', 'animated zoomInUp');
    $("#entity-modal-container").show();
}

function openForm(url, params) {
    if (params) {
        $.get(url, params, function(data) {
            entityModal(data);
            return true;
        });
    } else {
        $.get(url, function(data) {
            entityModal(data);
            return true;
        });
    }
    return false;
}

function closeForm() {
    $("#entity-modal-container").hide();
    $("#entity-modal-body-content").html('');
}

function timerWindow(url) {
    var newWindow = window.open(url,'Entity Project Timer','height=500,width=1000');
    if (window.focus) {newWindow.focus()}
    return false;
}

function refreshPage() {
    window.location.reload();
}

// Displaying scroll to top button
$(window).scroll(function(){
    if ($(this).scrollTop() > 100) {
        $('#scroll-btn').attr('class', 'animated zoomIn').fadeIn();
    } else {
        $('#scroll-btn').attr('class', 'animated zoomOut').fadeOut();
    }
});

$('#scroll-btn > i').click(function() {
    window.scrollTo(0,0);
});


// Quickmenu funcitonality

$('#quickMenuCloseBtn').click(function() {
    $('.quick-menu').hide();
});

function quickMenu(event){
    //alert(event.charCode);
    var key = event.charCode || event.which || event.keyCode;
    if (key === 96){
        $('.quick-menu').attr('class', 'quick-menu animated bounceIn').toggle();
    }

}