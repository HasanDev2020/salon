
        $(window).on("load", function () {
          var win = $(this);
          if(win.width() > 899)
          {
              //console.log('yes Large Screen');
                 //Team Slider
        $('#customers-teams').owlCarousel({
          loop: true,
          center: true,
          items: 3,
          animateIn: 'flipInX',
          smartSpeed:1000,
          margin: 0,
          autoplay: true,
          dots:true,
          touchDrag  : true,
          mouseDrag  : true,
          autoplayTimeout:8000 ,
                    checkVisibility: true,
          responsive: {
            0: {
              items: 1
            },

            899: {
              items: 2
            },
            1170: {
              items: 3
            }
          }
      });
          }
          else{
             // console.log('Small Screen');
                     //Team Slider
        $('#customers-teams').owlCarousel({
          loop: true,
          center: true,
          items: 3,
          animateIn: 'flipInX',
          smartSpeed:1000,
          margin: 0,
          autoplay: true,
          dots:true,
          touchDrag  : true,
          mouseDrag  : false,
          autoplayTimeout:8000 ,
                    checkVisibility: true,
          responsive: {
            0: {
              items: 1
            },

            899: {
              items: 2
            },
            1170: {
              items: 3
            }
          }
      });
          }

        });
