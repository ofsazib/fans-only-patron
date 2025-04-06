$("#ex1 .mb_slider").mbSlider({
    maxVal : MEMBERSHIP_FEE_MAX,
    onSlide:function(o){
        $("#"+o.id+"_val").find(".val").val($(o).mbgetVal());
        computePriceV2();
    },
    onSlideLoad:function(o){
        $("#"+o.id+"_val").find(".val").val($(o).mbgetVal());
        computePriceV2();
    }
});

$("#ex_new1 .mb_slider").mbSlider({
    maxVal : AUDIENCE_MAX,
    onSlide:function(o){
        $("#"+o.id+"_val").find(".val").val($(o).mbgetVal());
        computePriceV2();
    },
    onSlideLoad:function(o){
        $("#"+o.id+"_val").find(".val").val($(o).mbgetVal());
        computePriceV2();
    }
});

function computePriceV2() {
    let membersCount = parseInt($('.sl_audience').val());
    let monthlyPrice = parseInt($('.sl_membership').val());

    let computePrice = membersCount * monthlyPrice;
    let feeAmount = (computePrice*platformFee)/100;
    let finalEarnings = computePrice-feeAmount;

    let perMonth = $( '.per-month-v2' );

    console.log( computePrice,  platformFee, finalEarnings );

    perMonth.html( currencySymbol + finalEarnings.toLocaleString()  );
}

computePriceV2();