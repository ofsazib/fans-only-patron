$(document).ready(function() {

	$( "#buttonAjaxFilter" ).click( function(  ) {
		$( "#ajax-search-form" ).trigger('submit');
	});

	$("body").on("click", ".col-pagination-ajax ul li a", function(e) {

		e.preventDefault();

		// get page location
		var page = $( this ).attr( 'href' );

		// parse page number
		var pageNum = /page=([0-9]+)/;
		var matchPage = page.match( pageNum );
		var pageInt = parseInt( matchPage[1] );

		$( "#ajax-search-form" ).trigger('submit', [ pageInt ]);

		return false;

	});

	var ajaxFilterDomains = $( "#ajax-search-form" );

	ajaxFilterDomains.submit(function(event, pageNum) {

		console.log( 'form submitted' )
		console.log( 'Pagenum: ' + pageNum );

		event.preventDefault();

		var formData = $( this ).serialize();

		$.ajax({
	      type: 'POST',
	      url: '/ajax/domain_filtering?page=' + pageNum,
	      data: formData,
	      cache: false,
	      beforeSend:  function() {
	      	$( '.preload-search' ).show();
	      	$( '#ajax-filtered-domains' ).hide();
		  },
	      success: function(data){
	      	$( '.preload-search' ).hide();
	      	$( '#ajax-filtered-domains' ).show();
	        $( '#ajax-filtered-domains' ).html( data );
	      },
	      error: function(data) {

	      	$( '.preload-search' ).hide();
	      	$( '#ajax-filtered-domains' ).show();
	        sweetAlert("Oops...", data, "error");

	      }
	    });

		return false;
	});


	// stripe form - plan subscription
	var $form = $('#payment-form');

	$form.submit(function(event) {
	    // Disable the submit button to prevent repeated clicks:
	    $form.find('.submit').prop('disabled', true);

	    // Request a token from Stripe:
	    Stripe.createToken({
	        number: $('.card-number').val(),
	        cvc: $('.card-cvc').val(),
	        exp_month: $('.card-expiry-month').val(),
	        exp_year: $('.card-expiry-year').val()
      }, stripeResponseHandler);
	    //Stripe.card.createToken($form, stripeResponseHandler);

	    // Prevent the form from being submitted..:
	    return false;
	});

	function stripeResponseHandler(status, response) {
	  var $form = $('#payment-form');

	  if (response.error) {
	    // Show the errors on the form

	    //$form.find('.payment-errors').text(response.error.message);
	    $form.find('button').prop('disabled', false);

	    sweetAlert("Oops...", response.error.message, "error");

	  } else {

	    // response contains id and card, which contains additional card details
	    var token = response.id;
	    var customer = $('.name-on-card').val();
	    var email = $('.email-address').val();

	    // append values we need!
	    $form.append($('<input type="hidden" name="stripeToken" />').val(token));
	    $form.append($('<input type="hidden" name="customer" />').val(customer));
	    $form.append($('<input type="hidden" name="email" />').val(email));

	    // and submit
	    $form.get(0).submit();
	  }

	}

	// stripe form - buy domains
	var $form = $('#checkout-form');
	
	$form.submit(function(event) {
	    // Disable the submit button to prevent repeated clicks:
	    $form.find('.submit').prop('disabled', true);

	    // Request a token from Stripe:
	    Stripe.createToken({
	        number: $('.card-number').val(),
	        cvc: $('.card-cvc').val(),
	        exp_month: $('.card-expiry-month').val(),
	        exp_year: $('.card-expiry-year').val()
      }, stripeResponseHandler2);

	    // Prevent the form from being submitted..:
	    return false;
	});

	function stripeResponseHandler2(status, response) {
	  var $form = $('#checkout-form');

	  if (response.error) {
	    // Show the errors on the form

	    //$form.find('.payment-errors').text(response.error.message);
	    $form.find('button').prop('disabled', false);

	    sweetAlert("Oops...", response.error.message, "error");

	  } else {

	    // response contains id and card, which contains additional card details
	    var token = response.id;
	    var customer = $('.name-on-card').val();
	    var email = $('.email-address').val();
	    var domain = $('.domain-checkout').val();

	    // append values we need!
	    $form.append($('<input type="hidden" name="stripeToken" />').val(token));
	    $form.append($('<input type="hidden" name="customer" />').val(customer));
	    $form.append($('<input type="hidden" name="email" />').val(email));
	    $form.append($('<input type="hidden" name="domain" />').val(domain));

	    // and submit
	    $form.get(0).submit();
	  }

	}

	
	// add to cart buttons ( home + inner )
	$('.add-to-cart, .add-to-cart-inner').click(function(ev) {

		ev.preventDefault();

		var uri = $(this).attr('href');

		$.get( uri, function( r ) {

			swal({
				title: "Domain added to cart!",   
				text: r + "You can Checkout or Continue Shopping",   
				showCancelButton: true,   
				confirmButtonColor: "#DD6B55",   
				confirmButtonText: "Checkout",   
				cancelButtonText: "Continue Shopping",   
				closeOnConfirm: true,   
				closeOnCancel: true, 
				imageUrl: '/resources/assets/images/cart.png' ,
				html: true
			}, function(isConfirm) {   
				if (isConfirm) {     
					document.location.href = '/checkout';
				} 
			});

		}).fail(function(xhr, status, error) {
		    swal({ title: 'woops', text: error, type: "warning",  }); // or whatever
		});

		return false;

	});


	// remove from cart
	$('.cart-remove').click(function(ev) {
		ev.preventDefault();

		var removeUri = $(this).attr('href');

		swal({ 
			title: "Are you sure?", 
			type: "warning",   
			showCancelButton: true,   
			confirmButtonColor: "#DD6B55",   
			confirmButtonText: "Yes, remove it!",   
			closeOnConfirm: false 
		}, function(){   
			document.location.href = removeUri;
		});

		return false;
	});


	$('.paypalSubmit').click(function() {
		swal({   
			title: "Redirecting you to PayPal...", 
			text: 'It takes just a few seconds.',
			timer: 10000,   
			showConfirmButton: false, 
			imageUrl: '/resources/assets/images/ajax.gif'
		});
	});

	$( '#make-offer' ).submit(function( ev ) {
		ev.preventDefault();

		var formData = $( this ).serialize();

		$.ajax({
	      type: 'post',
	      url: '/make-offer',
	      data: formData,
	      dataType: 'json',
	      success: function(data){
	        $( '.make-offer-result' ).html( data.message );
	      },
	      error: function(data) {

	        var errors = data.responseJSON;
	        errorsHtml = '<br /><div class="alert alert-danger"><ul>';

	        $.each( errors, function( key, value ) {
	            errorsHtml += '<li>' + value[0] + '</li>'; //showing only the first error.
	        });

	        errorsHtml += '</ul></div>';
	            
	        $( '.make-offer-result' ).html( errorsHtml );

	      }
	    });

		return false;
	});
	
	$( '#make-financing' ).submit(function( ev ) {
		ev.preventDefault();

		var formData = $( this ).serialize();

		$.ajax({
	      type: 'post',
	      url: '/make-financing',
	      data: formData,
	      dataType: 'json',
	      success: function(data){
	        $( '.make-financing-result' ).html( data.message );
	      },
	      error: function(data) {

	        var errors = data.responseJSON;
	        errorsHtml = '<br /><div class="alert alert-danger"><ul>';

	        $.each( errors, function( key, value ) {
	            errorsHtml += '<li>' + value[0] + '</li>'; //showing only the first error.
	        });

	        errorsHtml += '</ul></div>';
	            
	        $( '.make-financing-result' ).html( errorsHtml );

	      }
	    });

		return false;
	});

});