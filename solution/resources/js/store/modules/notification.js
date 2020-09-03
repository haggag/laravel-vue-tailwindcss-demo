export const namespaced = true

export const state = {
    notifications: [],
    unreadCount: 0,
    active: false,
    notification: {
        message: '.',
        status: 'info'
    }
}

export const mutations = {
    SET_ACTIVE(state, active) {
        state.active = active
    },
    SET_NOTIFICATION(state, notification) {
        state.notification = notification
    },
    SET_UNREAD_COUNT(state, unreadCount) {
        state.unreadCount = unreadCount
    },
    INCREMENT_UNREAD_COUNT(state) {
        state.unreadCount += 1
    }
}

export const actions = {
    activate({commit}, notification) {
        commit('SET_ACTIVE', true)
        commit('SET_NOTIFICATION', notification)
        if ('persisted' in notification && notification.persisted) {
            commit('INCREMENT_UNREAD_COUNT')
        }
    },
    deactivate({commit}) {
        commit('SET_ACTIVE', false)
    },
    updateUnreadCount({commit}, unreadCount) {
        commit('SET_UNREAD_COUNT', unreadCount)
    }
}

export const getters = {
    active: state => {
        return state.active
    },
    notification: state => {
        return state.notification
    },
    unreadCount: state => {
        return state.unreadCount
    }
}
