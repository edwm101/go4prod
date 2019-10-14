import Vue from 'vue';

Vue.mixin({
    methods: {
        f_moneyFormat: value => value.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')
    }
})

