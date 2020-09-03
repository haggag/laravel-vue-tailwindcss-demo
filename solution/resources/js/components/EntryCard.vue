<template>
    <div @dblclick.prevent="toggleEditing" @mouseout="hovering = false" @mouseover="hovering = true"
         class="bg-white mb-4 shadow hover:shadow-lg overflow-hidden sm:rounded-lg">
        <div class="md:flex items-center justify-between">
            <div class="flex-1 sm:w-full px-4 py-2  sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    {{ entry.label }}
                </h3>
                <p class="mt-1 max-w-2xl text-sm leading-5 text-gray-400">
                    {{ entry.date_time | date }}
                </p>
            </div>

            <div class="flex-shrink-0 px-4 py-5 sm:px-6" v-show="hovering"><p><a
                @click.prevent="startEditing"
                class="cursor-pointer underline text-blue-700 hover:text-blue-900 text-sm font-bold px-2">EDIT</a><a
                @click.prevent="deleteEntry"
                class="cursor-pointer underline text-blue-700 hover:text-blue-900 text-sm font-bold px-2">DELETE</a></p>
            </div>
            <div class="hidden md:flex px-4 py-5 sm:px-6 font-medium text-lg">
                <currency-value :amount="entry.amount_cents" fraction_size="text-sm"
                                negative_color="text-gray-900" positive_color="text-green-400"/>
            </div>
            <div class="md:hidden px-4 py-5 sm:px-6 font-medium text-lg" v-show="!hovering">
                <currency-value :amount="entry.amount_cents" fraction_size="text-base"
                                negative_color="text-gray-900" positive_color="text-green-400"/>
            </div>

        </div>

        <transition
            enter-active-class="transition ease-out duration-100 "
            enter-class="transform opacity-0 scale-95"
            enter-to-class="transform opacity-100 scale-100"
            leave-active-class="transition ease-in duration-75"
            leave-class="transform opacity-100 scale-100"
            leave-to-class="transform opacity-0 scale-95"
        >
            <entry-form :entry="entry" @close="endEditing" mode="edit" submit_label="UPDATE ENTRY" v-if="editing"/>
        </transition>

    </div>
</template>

<script>
import CurrencyValue from "./CurrencyValue";

export default {
    components: {CurrencyValue},
    props: {
        entry: {
            type: Object,
            required: true
        }
    },

    data() {
        return {
            hovering: false,
            editing: false
        }
    },
    methods: {
        startEditing() {
            if (this.editing === false) {
                this.editing = true
                this.$emit('editing', this)
            }
        },
        endEditing() {
            this.editing = false
        },
        toggleEditing() {
            if (this.editing) {
                this.endEditing()
            } else {
                this.startEditing()
            }
        },
        deleteEntry() {
            this.$store
                .dispatch('entry/deleteEntry', this.entry)
                .then(() => {

                })
                .catch(() => {
                    // End Progress
                })
        }
    }
}
</script>
