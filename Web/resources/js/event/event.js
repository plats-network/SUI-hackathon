$(document).ready(function() {
  var confer_window = $(window);
  const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 4500,
    timerProgressBar: true,
    didOpen: (toast) => {
      toast.addEventListener('mouseenter', Swal.stopTimer)
      toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
  });

  $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

    //Check has param sucess_checkin
    var url = window.location.href;
    if (url.indexOf('sucess_checkin') != -1) {


        setTimeout(function(e) {
            //Show toast
            Toast.fire({
                icon: 'success',
                title: 'Checkin success 😀',
            })
        }, 1500);
    }else{
    }

  confer_window.on('load', function() {
    $('#preloader').fadeOut('1000', function() {
      $(this).remove();
    });
  });

  $('#info').on('click', function(e) {
    $('#e-menu').toggle('show');
  });


  if ($('#showModal').length > 0) {
    $('#showModal').on('click', function() {
      $('#myModal').modal();
    });

    $('.ticket--sp').on('click', function() {
      $('#myModal').modal();
      $('#successModal').modal();
    });
  }

  if ($('#editInfo').length > 0) {
    $('#editInfo').on('click', function() {
      $('#infoEditEmail').modal();
    })
  }

  if ($('#flagU').length > 0) {
    var flag = $('#flagU').data('flag');
    if (flag == 1) {
      $('#infoEditEmail').modal();
    }
  }

  $('.aws').on('click', function(e) {
    var id = $(this).data('id'),
      result = $(this).data('result'),
      url = $('#taskQ').data('url'),
      num = $('#taskQ').data('num'),
      url2 = $('#taskQ').data('url2');

    const Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 1500,
      timerProgressBar: true,
      didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
      }
    });

    if (result == 1) {
      Toast.fire({
        icon: 'success',
        title: 'Chính xác 😀'
      })
    } else {
      Toast.fire({
        icon: 'error',
        title: 'Sai rồi 😥'
      })
    }

    setTimeout(function(e) {
      var aLink = num == 1 ? url2 : url;
      window.location.replace(aLink);
    }, 1500);
  });

  $('.price-package').on('click', function(e) {
    var price = $(this).data('price'),
      id = $(this).data('id');
    $('#amount').val(price);
    $('#sponsorId').val(id);
    $('#amount').focus();
    $('#cSponsor').removeAttr('disabled');
    $('#cSponsor').removeClass('disabled');
  });

  $(document).on('click', '#cSponsor', function (event) {
    var type = $(this).data('type'),
    amount = $('#amount').val(),
    url = $(this).data('url'),
    sponsor_id = $('#sponsorId').val(),
    id = $(this).data('id');

    setTimeout(function(e) {
      window.location.replace(url + "?type="+type+"&amount="+amount+'&event_id='+id+'&detail_id='+sponsor_id);
    }, 1000);
  });

  $(document).on('click', '#subSponsor', function (event) {
    $(this).attr('disabled', true);

    var id = $(this).data('id'),
      sponsor_id = $(this).data('sponsor-id'),
      detail_id = $(this).data('detail-id'),
      amount = $(this).data('amount'),
      url = $(this).data('url'),
      redir = $(this).data('redir'),
      note = $(this).data('note');

      $.ajax({
          url :  url,
          type: 'POST',
          data: {
            task_id: id,
            sponsor_id: sponsor_id,
            sponsor_detail_id: detail_id,
            amount: amount,
            note: note
          },
          success: function(data) {
              Toast.fire({
                icon: 'success',
                title: 'Submit success 😀'
              })

              setTimeout(function(e) {
                window.location.replace(redir);
              }, 1500);
          },
          error: function() {
            Toast.fire({
              icon: 'error',
              title: 'Errors 😥'
            })
          }
      });
  });

  if ($('#nftC').length > 0) {
    var nft = $('#nftC').data('nft');
    if (nft == 1 || nft == '1') {
      $('#nftC').modal();
    }
  }

  if ($('#reset-NFT').length > 0) {
    $(document).on('click', '#reset-NFT', function (event) {
      var url = $(this).data('url'),
        url_reset = $(this).data('reset');
      $.ajax({
          url :  url_reset,
          type: 'GET',
          success: function(data) {
            Toast.fire({
              icon: 'success',
              title: 'Redirect to Claim NFT.'
            });
            setTimeout(function(e) {
              // window.location.replace(url);
              window.open(url,'_blank');
              window.location.reload();
            }, 500);
          },
          error: function() {
            Toast.fire({
              icon: 'error',
              title: 'Error'
            });
            setTimeout(function(e) {
              window.location.reload();
            }, 900);
          }
      });
    });
  }

  if ($('#see1').length > 0) {
    $(document).on('click', '#see1', function (event) {
      $('#seeMore1').removeAttr('style', 'display: none');
      $('#see1').addClass('lessMore1').html('Less more');
    })
  }

  $(document).on('click', '.lessMore1', function (event) {
    $('#seeMore1').attr('style', 'display: none');
    $('.lessMore1').removeClass('lessMore1').html('Read more');
  })

  if ($('#s').length > 0) {
    $(document).on('click', '#see1', function (event) {

    })
  }

  $("#infoForm").validate({
    onfocusout: false,
    onkeyup: false,
    onclick: false,
    rules: {
      "email": {
        required: true,
        email: true
      },
      "name": {
        required: true
      },
    },
    messages: {
      "email": {
        required: "Please input email",
        email: 'Email format fail'
      },
      "name": {
        required: "Please input name"
      },
    }
  });


  $('.nav-tabs a').click(function(){
    $(this).tab('show');
  })

  $('.nav-tabs a[href="#session"]').tab('show')
  $('.nav-tabs a:first').tab('show')
  $('.nav-tabs li:eq(0) a').tab('show')

  var welcomeSlider = $('.welcome-slides');
  welcomeSlider.owlCarousel({
    items: 1,
    loop: true,
    autoplay: false,
    smartSpeed: 1000,
    autoplayTimeout: 10000,
    nav: true,
    navText: [('<i class="fa fa-angle-left"></i>'), ('<i class="fa fa-angle-right"></i>')]
  })

  welcomeSlider.on('translate.owl.carousel', function() {
    var layer = $("[data-animation]");
    layer.each(function() {
      var anim_name = $(this).data('animation');
      $(this).removeClass('animated ' + anim_name).css('opacity', '0');
    });
  });

  $("[data-delay]").each(function() {
    var anim_del = $(this).data('delay');
    $(this).css('animation-delay', anim_del);
  });

  $("[data-duration]").each(function() {
    var anim_dur = $(this).data('duration');
    $(this).css('animation-duration', anim_dur);
  });

  welcomeSlider.on('translated.owl.carousel', function() {
    var layer = welcomeSlider.find('.owl-item.active').find("[data-animation]");
    layer.each(function() {
      var anim_name = $(this).data('animation');
      $(this).addClass('animated ' + anim_name).css('opacity', '1');
    });
  });

  $("#scrollDown").on('click', function() {
    $('html, body').animate({
      scrollTop: $("#about").offset().top - 75
    }, 800);
  });

  var clientArea = $('.client-area');
  clientArea.owlCarousel({
    items: 2,
    loop: true,
    autoplay: true,
    smartSpeed: 1000,
    margin: 40,
    autoplayTimeout: 7000,
    nav: true,
    navText: [('<i class="fa fa-angle-left"></i>'), ('<i class="fa fa-angle-right"></i>')],
    responsive: {
      0: {
        items: 1
      },
      576: {
        items: 1,
        margin: 15
      },
      992: {
        items: 1,
        margin: 20
      },
      1200: {
        items: 1,
        margin: 40
      }
    }
  });
  $('.portfolio-menu button.btn').on('click', function() {
    $('.portfolio-menu button.btn').removeClass('active');
    $(this).addClass('active');
  })
  $('.search-btn').on('click', function() {
    $('.search-form').toggleClass('search-form-active');
  })
  confer_window.on('scroll', function() {
    if (confer_window.scrollTop() > 0) {
      $('.header-area').addClass('sticky');
    } else {
      $('.header-area').removeClass('sticky');
    }
  });
  $('[data-toggle="tooltip"]').tooltip();
  // $('.jarallax').jarallax({
  //   speed: 0.5
  // });
  confer_window.scrollUp({
    scrollSpeed: 1000,
    scrollText: '<i class="arrow_carrot-up"</i>'
  });
  $('a[href="#"]').on('click', function($) {
    $.preventDefault();
  });


  var pricingTable = $(".single-ticket-pricing-table");
  pricingTable.on("mouseenter", function() {
    pricingTable.removeClass("active");
    $(this).addClass("active");
  });

  // Menu
  var navContainer = $('.classy-nav-container');
  var classy_nav = $('.classynav ul');
  var classy_navli = $('.classynav > ul > li');
  var navbarToggler = $('.classy-navbar-toggler');
  var closeIcon = $('.classycloseIcon');
  var navToggler = $('.navbarToggler');
  var classyMenu = $('.classy-menu');
  var var_window = $(window);
  var breakpoint = 991;
  var openCloseSpeed = 500;
  var megaopenCloseSpeed = 800;
  var sideMenu = true;
  //Check has param sucess_checkin
    var url = window.location.href;
    if (url.indexOf('success_checkin') != -1) {
        //Show toast
        Toast.fire({
          icon: 'success',
          title: 'Checkin success 😀'
        })
    }

  navbarToggler.on('click', function() {
    navToggler.toggleClass('active');
    classyMenu.toggleClass('menu-on');
  });
  closeIcon.on('click', function() {
    classyMenu.removeClass('menu-on');
    navToggler.removeClass('active');
  });
  classy_navli.has('.dropdown').addClass('cn-dropdown-item');
  classy_navli.has('.megamenu').addClass('megamenu-item');
  classy_nav.find('li a').each(function() {
    if ($(this).next().length > 0) {
      $(this).parent('li').addClass('has-down').append('<span class="dd-trigger"></span>');
    }
  });
  classy_nav.find('li .dd-trigger').on('click', function(e) {
    e.preventDefault();
    $(this).parent('li').children('ul').stop(true, true).slideToggle(openCloseSpeed);
    $(this).parent('li').toggleClass('active');
  });
  $('.megamenu-item').removeClass('has-down');
  classy_nav.find('li .dd-trigger').on('click', function(e) {
    e.preventDefault();
    $(this).parent('li').children('.megamenu').slideToggle(megaopenCloseSpeed);
  });

  function breakpointCheck() {
    var windoWidth = window.innerWidth;
    if (windoWidth <= breakpoint) {
      navContainer.removeClass('breakpoint-off').addClass('breakpoint-on');
    } else {
      navContainer.removeClass('breakpoint-on').addClass('breakpoint-off');
    }
  }
  breakpointCheck();
  var_window.on('resize', function() {
    breakpointCheck();
  });
  if (sideMenu === true) {
    navContainer.addClass('sidebar-menu-on').removeClass('breakpoint-off');
  }
});
