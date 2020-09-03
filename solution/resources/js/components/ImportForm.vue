<template>
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <form @submit.prevent="submit" class="w-full">
            <div class="border-b border-t border-gray-200 py-8">
                <div class="flex flex-wrap mx-3 mb-2">
                    <div class="w-full  px-3 mb-6 md:mb-0">
                        <label class="block uppercase tracking-wide text-gray-500 text-sm font-bold mb-2"
                        >
                            CSV FILE
                        </label>
                        <div :class="[$v.file.$error  ? 'border-red-500' : 'border-gray-200' ]"
                             class="border  border-gray-200 bor p-3 rounded md:flex items-center justify-between">
                            <div>
                                <span>{{ filename }}</span>
                            </div>
                            <div>
                                <label class="cursor-pointer text-blue-600 hover:text-blue-800 underline" for="file">Select
                                    file</label>
                                <input @change="selectFile" class="hidden" id="file" name="file" type="file">
                            </div>
                        </div>

                        <p class="text-red-500 text-xs italic" v-if="$v.file.$error && !$v.file.required">This field is
                            required.</p>
                        <p class="text-red-500 text-xs italic"
                           v-if="$v.file.$error && !$v.file.isCSV">Must be CSV file with the .csv extension.</p>
                        <p class="text-red-500 text-xs italic"
                           v-if="$v.file.$error && !$v.file.isSizeValid">Size must be less than 1 MB.</p>
                        <p class="text-red-500 text-xs italic" v-if="error">{{ error }}</p>
                    </div>

                </div>

            </div>
            <div class="bg-white px-4 py-6 sm:px-6 sm:flex sm:flex-row-reverse">
                  <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                    <button :disabled="submitStatus === 'PENDING'"
                            class="inline-flex justify-center w-full rounded-md border border-transparent px-6 py-4 bg-blue-600 text-base leading-6 font-medium font-bold text-white  hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5"
                            type="submit">
                      IMPORT
                    </button>
                  </span>
                <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
                    <button @click.prevent="close"
                            class="inline-flex justify-center w-full rounded-md -300 px-6 py-4 bg-indigo-100 text-base leading-6 font-bold font-medium text-gray-500  hover:text-gray-600 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5"
                            type="button">
                      CANCEL
                    </button>
                  </span>
            </div>
        </form>
    </div>
</template>

<script>
import EntryService from '../services/EntryService.js'
import {required} from 'vuelidate/lib/validators'
import store from '../store/store'

const isCSV = (value) => {
    if (!value) {
        return true;
    }
    return value.type === 'text/csv'
}

const isSizeValid = (value) => {
    if (!value) {
        return true;
    }
    return (value.size < 1048570);
}

export default {
    mounted() {

    },

    data() {

        return {
            file: null,
            filename: '',
            submitStatus: null,
            error: null,
        }
    },
    methods: {
        close() {
            this.$emit('close')
        },
        selectFile(event) {
            this.error = null
            if (!event.target.files.length) {
                return
            }

            // `files` is always an array because the file input may be in multiple mode
            this.file = event.target.files[0]
            // console.log('file.type.' + this.file.type)
            this.filename = event.target.files[0].name
            this.$v.file.$touch();
        },
        importFile() {
            // Start progress
            EntryService.uploadFile(this.file)
                .then(response => {
                    // success
                    this.submitStatus = 'OK'
                    // console.log('file uploaded ', response.data)
                    store.dispatch('user/lock')
                    store.dispatch('notification/activate', {
                        message: response.data.message,
                        status: response.data.status
                    }).then(() => {
                        this.$emit('close')
                    })


                })
                .catch(error => {
                    this.submitStatus = 'ERROR'
                    this.error = error
                    // console.log('file upload filed ', error)
                })
        },

        submit() {

            this.$v.$touch()
            if (this.$v.$invalid) {
                this.submitStatus = 'ERROR'
            } else {
                this.submitStatus = 'PENDING'
                this.importFile()
            }
        }
    },
    validations: {
        file: {required, isSizeValid, isCSV}  // TODO add required
    }
}
</script>
