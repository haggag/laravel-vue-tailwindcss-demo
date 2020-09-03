import Vue from 'vue'
import EntryService from '../../services/EntryService.js'
import moment from 'moment'

export const namespaced = true

export const state = {
    entries: [],
    paginator: {
        current_page: 1,
        from: 1,
        last_page: 0,
        path: "",
        per_page: 100,
        to: 0,
        total: 0
    }
}

export const mutations = {

    SET_ENTRIES(state, entries) {
        state.entries = entries
    },
    SET_PAGINATOR(state, paginator) {
        state.paginator = paginator
    },
    UPDATE_PAGINATOR(state, delta) {
        const paginator = {...state.paginator}

        if (paginator.total === 0) {
            paginator.from = paginator.to = paginator.total = 1
        } else {
            paginator.total += 1
            paginator.last_page = Math.ceil(paginator.total / paginator.per_page)
            if (paginator.to % paginator.per_page !== 0) {
                paginator.to += 1
            }
        }
        state.paginator = paginator
    },
    // Trim excess entries (i.e. after add new entry)
    TRIM_ENTRIES(state, length) {
        if (Object.keys(state.entries).length !== 0) {
            const bufferLength = Object.values(state.entries).reduce((acc, item) => acc + item.length, 0)
            if (bufferLength > length) { // ideally difference should be 1
                const oldestKey = Object.keys(state.entries).sort()[0]
                state.entries[oldestKey].pop()
                if (state.entries[oldestKey].length === 0) {
                    Vue.delete(state.entries, oldestKey)
                }
            }
        }
    },
    ADD_ENTRY(state, entry) {
        const tag = entry.date_time.format('YYYY-MM-DD');
        if (!(tag in state.entries)) {
            Vue.set(state.entries, tag, [])
        }

        state.entries[tag].push(entry)
        state.entries[tag].sort((a, b) => (a.date_time > b.date_time) ? 1 : ((b.date_time > a.date_time) ? -1 : 0)).reverse()
    },
    DELETE_ENTRY(state, entry) {
        const tag = entry.date_time.format('YYYY-MM-DD')
        const removeIndex = state.entries[tag].map(item => item.id).indexOf(entry.id)
        ~removeIndex && state.entries[tag].splice(removeIndex, 1)
        if (state.entries[tag].length === 0) {
            Vue.delete(state.entries, tag)
        }
    },
    UPDATE_ENTRY(state, payload) {
        const tag = payload.date_time.format('YYYY-MM-DD')
        const entry = payload.entry
        const index = state.entries[tag].map(item => item.id).indexOf(entry.id)
        const entry_to_update = state.entries[tag][index]

        if (entry.hasOwnProperty('amount_cents')) {
            entry_to_update.amount_cents = entry.amount_cents
        }

        if (entry.hasOwnProperty('date_time')) {
            entry_to_update.date_time = entry.date_time
            const new_tag = entry.date_time.format('YYYY-MM-DD')
            if (new_tag !== tag) {
                ~index && state.entries[tag].splice(index, 1)
                if (state.entries[tag].length === 0) {
                    Vue.delete(state.entries, tag)
                }
                // Insert in new bucket
                if (!(new_tag in state.entries)) {
                    Vue.set(state.entries, new_tag, [])
                }
                state.entries[new_tag].push(entry)
                state.entries[new_tag].sort((a, b) => (a.date_time > b.date_time) ? 1 : ((b.date_time > a.date_time) ? -1 : 0)).reverse()
            } else {
                state.entries[tag].sort((a, b) => (a.date_time > b.date_time) ? 1 : ((b.date_time > a.date_time) ? -1 : 0)).reverse()
            }
        }
        if (entry.hasOwnProperty('label')) {
            entry_to_update.label = entry.label
        }

    }
}

export const actions = {
    // initBalance({commit}, balance) {
    //     commit('UPDATE_BALANCE', balance)
    // },
    createEntry({commit, dispatch, getters, state}, entry) {
        return EntryService.postEntry(entry)
            .then(response => {
                entry['id'] = response.data.id
                commit('ADD_ENTRY', entry)
                // commit('UPDATE_BALANCE', response.data.total_cents)
                dispatch('user/updateBalance', response.data.total_cents, {root: true})
                commit('TRIM_ENTRIES', state.paginator.per_page)
                commit('UPDATE_PAGINATOR', 1)
                const notification = {
                    type: 'success',
                    message: 'Your entry has been created!'
                }
                // TODO dispatch('notification/add', notification, { root: true })
            })
            .catch(error => {
                const notification = {
                    type: 'error',
                    message: 'There was a problem creating your entry: ' + error.message
                }
                // TODO dispatch('notification/add', notification, { root: true })
                throw error
            })
    },
    updateEntry({commit, dispatch, getters, state}, {entry, date_time}) {
        return EntryService.updateEntry(entry)
            .then(response => {
                const payload = {'entry': entry, 'date_time': date_time}
                commit('UPDATE_ENTRY', payload)
                if (entry.hasOwnProperty('amount_cents')) {
                    // commit('UPDATE_BALANCE', response.data.total_cents)
                    dispatch('user/updateBalance', response.data.total_cents, {root: true})
                }
                // commit('TRIM_ENTRIES', state.paginator.per_page)

                const notification = {
                    type: 'success',
                    message: 'Your entry has been created!'
                }
                // TODO dispatch('notification/add', notification, { root: true })
            })
            .catch(error => {
                const notification = {
                    type: 'error',
                    message: 'There was a problem creating your entry: ' + error.message
                }
                // TODO dispatch('notification/add', notification, { root: true })
                throw error
            })
    },
    deleteEntry({commit, dispatch, getters, state}, entry) {
        return EntryService.deleteEntry(entry.id)
            .then(response => {
                commit('DELETE_ENTRY', entry)
                // commit('UPDATE_BALANCE', response.data.total_cents)
                dispatch('user/updateBalance', response.data.total_cents, {root: true})
                // commit('TRIM_ENTRIES', state.paginator.per_page)
                commit('UPDATE_PAGINATOR', 1)
                const notification = {
                    type: 'success',
                    message: 'Your event has been created!'
                }
                // TODO dispatch('notification/add', notification, { root: true })
            })
            .catch(error => {
                const notification = {
                    type: 'error',
                    message: 'There was a problem creating your entry: ' + error.message
                }
                // TODO dispatch('notification/add', notification, { root: true })
                throw error
            })
    },

    refetchCurrentPage({dispatch, state}) {
        return dispatch('fetchEntries', {
            page: state.paginator.current_page
        })
    },
    fetchEntries({commit, dispatch, state}, {page}) {
        return EntryService.getEntries(page)
            .then(response => {
                let data = response.data.data.reduce(function (buckets, entry) {
                    entry.date_time = moment.utc(entry.date_time).local()
                    const tag = entry.date_time.format('YYYY-MM-DD');
                    buckets[tag] = buckets[tag] || [];
                    buckets[tag].push(entry);
                    buckets[tag].sort((a, b) => (a.date_time > b.date_time) ? 1 : ((b.date_time > a.date_time) ? -1 : 0)).reverse()
                    return buckets;
                }, Object.create(null));

                commit('SET_ENTRIES', data)
                commit('SET_PAGINATOR', response.data.meta)
            })
            .catch(error => {
                const notification = {
                    type: 'error',
                    message: 'There was a problem fetching entries: ' + error.message
                }
                // TODO dispatch('notification/add', notification, { root: true })
            })
    }
}

export const getters = {
    // totalBalanceAsDollars: state => {
    //     return state.totalBalance / 100.0
    // },
    getEventById: state => id => {
        return state.events.find(event => event.id === id)
    },
    currentBufferLength: state => {
        let length = 0
        if (Object.keys(state.entries).length !== 0) {
            length = Object.values(state.entries).reduce((acc, item) => acc + item.length, 0)
        }
        return length
    },
    sortedKeys: state => {
        return Object.keys(state.entries).sort().reverse()
    },
    hasPreviousPage: state => {
        return state.paginator.current_page !== 1
    },
    hasNextPage: state => {
        return state.paginator.total > state.paginator.current_page * state.paginator.per_page
    },
    currentPage: state => {
        return state.paginator.current_page
    },
    lastPage: state => {
        return state.paginator.last_page
    },
    showingItemsFrom: state => {
        return state.paginator.from
    },
    showingItemsTo: state => {
        return state.paginator.to
    },
    totalItems: state => {
        return state.paginator.total
    },
    empty: state => {
        return Object.keys(state.entries).length === 0
    }
}

