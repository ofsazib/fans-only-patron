<template>
  <div class="dropdown">

    <a href="javascript:void(0)" class="btn btn-primary btn-sm mb-2 dropdown-toggle" :id="'premiumPostsLink-'+messageId" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      {{ unlockLinkTitle }} {{ currencySymbol }}{{ price }}
    </a>

    <div class="dropdown-menu" :aria-labelledBy="'premiumPostsLink-'+messageId">

      <!-- Stripe Button -->
      <a class="dropdown-item" :href="'/unlock-message/Card/' + messageId" v-if="cardGateway === 'Stripe' && pmCount > 0">
        {{ payWithCard }}
      </a>
      <a class="dropdown-item" :href="'/unlock-message/Card/' + messageId" v-if="cardGateway === 'Stripe' && pmCount < 1">
        {{ addCard }}
      </a>

      <!-- MercadoPago, TransBank, CCBill Buttons -->
      <a class="dropdown-item" :href="'/unlock-message/Card/' + messageId" v-if="cardGateway === 'MercadoPago' || cardGateway === 'TransBank' || cardGateway === 'CCBill'">
        {{ payWithCard }}
      </a>

      <!-- PayPal Button -->
      <a class="dropdown-item" :href="'/unlock-message/PayPal/' + messageId" v-if="paypalEnabled === 'Yes'">
        PayPal
      </a>

      <!-- PayStack Button -->
      <a class="dropdown-item" :href="'/unlock-message/Card/' + messageId" v-if="cardGateway === 'PayStack' && pmCount > 0">
        {{ payWithCard }}
      </a>
      <a class="dropdown-item" :href="'/unlock-message/Card/' + messageId" v-if="cardGateway === 'PayStack' && pmCount < 1">
        {{ addCard }}
      </a>

      <!-- Crypto - CoinPayments.net Button -->
      <a class="dropdown-item" :href="'/unlock-message/Card/' + messageId" v-if="cardGateway === 'Crypto'">
        {{ payWithCrypto }}
      </a>
    </div>

  </div>
</template>

<script>
export default {
  props: {
    price: Number,
    messageId: Number,
    paymentMethodsCount: Number,
    pmCount: Number
  },
  data() {
    return {
      paypalEnabled: window.paypalEnabled,
      cardGateway: window.cardGateway,
      unlockLinkTitle: window.unlockLinkTitle,
      currencySymbol: window.currencySymbol,
      payWithCard: window.card,
      addCard: window.addCard,
      cardRoute: window.cardRoute,
      loginRoute: window.loginRoute,
      payWithCrypto: window.payWithCrypto,
      enableMediaDl: window.enableMediaDl
    }
  }
}
</script>