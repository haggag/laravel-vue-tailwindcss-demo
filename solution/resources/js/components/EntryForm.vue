<template>
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <form @submit.prevent="submit" class="w-full">
            <div class="border-b border-t border-gray-200 py-8">
                <div class="flex flex-wrap mx-3 mb-2">
                    <div class="w-full md:w-2/5 px-3 mb-6 md:mb-0">
                        <label class="block uppercase tracking-wide text-gray-500 text-sm font-bold mb-2"
                               for="grid-city">
                            Label
                        </label>
                        <input :class="[$v.label.$error  ? 'border-red-500' : 'border-gray-200' ]"
                               class="form-input block w-full pl-3 py-3 sm:text-sm sm:leading-5 text-gray-500"
                               id="grid-city"
                               type="text" v-model.trim="$v.label.$model">
                        <p class="text-red-500 text-xs italic" v-if="$v.label.$error && !$v.label.required">Please fill
                            out this field.</p>
                        <p class="text-red-500 text-xs italic" v-if="$v.label.$error && !$v.label.maxLength">Label must
                            have at most {{ $v.label.$params.maxLength.max }} letters.</p>
                    </div>
                    <div class="w-full md:w-2/5 px-3 mb-6 md:mb-0">
                        <label class="block uppercase tracking-wide text-gray-500 text-sm font-bold mb-2"
                               for="meeting-time">
                            Date
                        </label>

                        <input class="form-input block w-full pl-3 py-3 sm:text-sm text-gray-500 sm:leading-5 "
                               id="meeting-time"
                               name="meeting-time" type="datetime-local"
                               v-model="$v.date_time.$model"
                        >
                    </div>
                    <div class="w-full md:w-1/5 px-3 mb-6 md:mb-0">
                        <label class="block uppercase tracking-wide text-gray-500 text-sm font-bold mb-2" for="amount">Amount</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                      <span class="text-gray-500 sm:text-sm sm:leading-5">
                                        $
                                      </span>
                            </div>
                            <input :class="[$v.amount_dollars.$error  ? 'border-red-500' : 'border-gray-200' ]"
                                   class="form-input block w-full pl-7 py-3 sm:text-sm sm:leading-5 text-gray-500"
                                   id="amount"
                                   placeholder="0.00"
                                   type="text"
                                   v-model.trim="$v.amount_dollars.$model">
                        </div>
                        <p class="text-red-500 text-xs italic"
                           v-if="$v.amount_dollars.$error && !$v.amount_dollars.required">Required field.</p>
                        <p class="text-red-500 text-xs italic"
                           v-if="$v.amount_dollars.$error && !$v.amount_dollars.decimal">Must be decimal</p>
                        <p class="text-red-500 text-xs italic"
                           v-if="$v.amount_dollars.$error && !$v.amount_dollars.nonZero">Cannot be zero</p>


                    </div>
                </div>

            </div>
            <div class="bg-white px-4 py-6 sm:px-6 sm:flex sm:flex-row-reverse">
                  <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                    <button :disabled="submitStatus === 'PENDING'"
                            class="inline-flex justify-center w-full rounded-md border border-transparent px-6 py-4 bg-blue-600 text-base leading-6 font-medium font-bold text-white  hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5"
                            type="submit">
                      {{ this.submit_label }}
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
import moment from 'moment'
import {decimal, maxLength, required} from 'vuelidate/lib/validators'

const nonZero = (value) => parseFloat(value) !== 0

export default {
    props: {
        mode: {
            type: String,
            required: true
        },
        submit_label: {
            type: String,
            required: true
        },
        entry: {
            type: Object,
            required: false
        }
    },

    mounted() {
        if (this.mode === 'edit') {
            this.label = this.entry.label
            this.date_time = this.entry.date_time.format('YYYY-MM-DD[T]HH:mm')
            this.amount_dollars = this.entry.amount_cents / 100
        }
    },

    data() {
        Date.prototype.toDateInputValue = (function () {
            var local = new Date(this);
            local.setMinutes(this.getMinutes() - this.getTimezoneOffset());
            return local.toJSON().slice(0, 16);
        });
        return {
            submitStatus: null,
            label: '',
            date_time: new Date().toDateInputValue(),
            amount_dollars: null
        }
    },
    methods: {
        close() {
            this.$emit('close')
        },
        createEntry() {
            // Start progress
            this.$store
                .dispatch('entry/createEntry', this.entry_to_submit)
                .then(() => {
                    this.submitStatus = 'OK'
                    this.$emit('close')
                })
                .catch(() => {
                    // End progress
                })
        },
        updateEntry() {
            // Start progress
            let entry_to_update = {}
            if (this.label !== this.entry.label) {
                entry_to_update['label'] = this.label
            }
            if (this.date_time !== this.entry.date_time.format('YYYY-MM-DD[T]HH:mm')) {
                entry_to_update['date_time'] = moment(this.date_time)
            }
            const new_cents = this.amount_dollars * 100
            if (new_cents !== this.entry.amount_cents) {
                entry_to_update['amount_cents'] = new_cents
            }
            if (Object.keys(entry_to_update).length === 0) {
                this.$emit('close')
            } else {
                entry_to_update['id'] = this.entry.id
                const payload = {'entry': entry_to_update, 'date_time': this.entry.date_time}

                this.$store
                    .dispatch('entry/updateEntry', payload)
                    .then(() => {
                        this.submitStatus = 'OK'
                        this.$emit('close')
                    })
                    .catch(() => {
                        // End progress
                    })
            }
        },
        submit() {
            this.$v.$touch()
            if (this.$v.$invalid) {
                this.submitStatus = 'ERROR'
            } else {
                this.submitStatus = 'PENDING'
                if (this.mode === 'create') {
                    this.createEntry()
                } else {
                    this.updateEntry()
                }
            }
        }
    },
    validations: {
        label: {required, maxLength: maxLength(80)},
        amount_dollars: {required, nonZero, decimal},
        date_time: {required}
    },
    computed: {
        entry_to_submit() {
            return {
                label: this.label,
                date_time: moment(this.date_time),
                amount_cents: Math.trunc(parseFloat(this.amount_dollars) * 100)
            }
        }
    }
}
</script>
