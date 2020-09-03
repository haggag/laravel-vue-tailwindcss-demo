export const namespaced = true

export const state = {
    user: {
        id: 0,
        name: '',
        api_token: '',
        total_cents: 0
    }
}

export const mutations = {
    SET_USER(state, user) {
        state.user = user
    },
    SET_LOCK(state, value) {
        state.user.locked = value
    },
    SET_BALANCE(state, total_cents) {
        state.user.total_cents = total_cents
    },
}

export const actions = {
    init({commit}, user) {
        commit('SET_USER', user)
    },
    updateBalance({commit}, balance) {
        commit('SET_BALANCE', balance)
    },
    lock({commit}) {
        commit('SET_LOCK', true)
    },
    unlock({commit}) {
        commit('SET_LOCK', false)
    },
}

export const getters = {
    user: state => {
        return state.user
    },
    totalBalanceAsDollars: state => {
        return state.user.total_cents / 100.0
    },
}
