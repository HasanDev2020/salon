let sideBar = document.getElementById('offcanvas-slide');
function CheckSideLarge(){
    UIkit.offcanvas(sideBar,{
        'overlay': false,
        'bgClose':false,
        'escClose':false
    }).show();
}

function CheckSideSmall(){
    UIkit.offcanvas(sideBar,{
        'overlay': true,
        'bgClose':true,
        'escClose':true
    }).hide();
}




//let media =  window.matchMedia("(max-width: 899px)");


$(window).on("load", function () {
    console.log('window is load')
    $(".loader").fadeOut();
    $("#preloder").delay(400).fadeOut("slow");
    var win = $(this);
    console.log(win.width());
    if(win.width() > 899)
    {
        //console.log('yes Large Screen');
        CheckSideLarge();
    }
    else{
       // console.log('Small Screen');
        CheckSideSmall();
    }

  });
  $(window).on("resize", function () {
    //console.log("windo is resize");
    var win = $(this); //this = windowvar
    if(win.width() > 899)
    {
        if(sideBar.classList.contains('uk-open'))
        {
            return false;
        }
        else{
            console.log('yes Large Screen');
            CheckSideLarge();
            console.log(sideBar.classList.contains('.uk-open'));
        }

    }
    else{
        //console.log('Small Screen');
        CheckSideSmall();
    }
  });



  $(function(){
    $(window).scroll(function(){
      if(window.scrollY > 80)
      {
        $('.uk-navbar-container img').addClass('SmallLogo');
        $('.uk-navbar-container').addClass('largeBG');

      }
      else{
        $('.uk-navbar-container img').removeClass('SmallLogo');
        $('.uk-navbar-container').removeClass('largeBG');

      }
        console.log('scrolled');
    });
  });
