import axios from 'axios'
import moment from 'moment';
/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

axios.defaults.headers.common = {
    'X-CSRF-TOKEN': typeof Laravel !== 'undefined' ? Laravel.csrfToken : "",
    'X-Requested-With': 'XMLHttpRequest',
    'Authorization': 'Bearer ' + (typeof Laravel !== 'undefined' ? Laravel.apiToken : ""),
};

const apiClient = axios.create({
    withCredentials: false, // This is the default
    timeout: 10000
})

export default {
    getEntries(page = 1) {
        return apiClient.get('/api/entries?page=' + page)
    },
    getEntry(id) {
        return apiClient.get('/api/entries/' + id)
    },
    deleteEntry(id) {
        return apiClient.delete('/api/entries/' + id)
    },
    postEntry(entry) {
        const entry_copy = {...entry}
        entry_copy.date_time = moment(entry_copy.date_time).utc().format()
        return apiClient.post('/api/entries', entry_copy)
    },
    updateEntry(entry) {
        const entry_copy = {...entry}
        return apiClient.put('/api/entries/' + entry_copy.id, entry_copy)
    },
    uploadFile(file) {
        const data = new FormData();
        data.append('file', file);

        return apiClient.post("/api/files", data);

    },
}
