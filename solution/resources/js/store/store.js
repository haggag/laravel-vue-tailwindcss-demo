import Vue from 'vue'
import Vuex from 'vuex'
import * as entry from './modules/entry.js'
import * as notification from './modules/notification.js'
import * as user from './modules/user.js'

Vue.use(Vuex)

export default new Vuex.Store({
    modules: {
        entry,
        user,
        notification
    }
})
