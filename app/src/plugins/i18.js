import Vue from 'vue';
import VueI18n from 'vue-i18n'
import messages from '@/common/locale'

Vue.use(VueI18n)

export default new VueI18n({
    locale: 'fr',
    fallbackLocale: 'fr',
    messages,
})