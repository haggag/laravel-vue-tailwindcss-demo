<template>
    <div>
        <div class="flex items-center justify-between h-16">
            <div class="">
                <h3 class="text-sm leading-6 font-bold text-gray-400">
                    {{ title | date('short') | uppercase }}
                </h3>

            </div>
            <div class="px-4 py-5  sm:px-6">
                <p class="font-bold text-lg">
                    <currency-value :amount="total" fraction_size="text-base" negative_color="text-gray-400"
                                    positive_color="text-green-400"/>
                </p>
            </div>
        </div>
        <entry-card :entry="e" :key="e.id" v-for="e in entries" v-on="$listeners"/>
    </div>
</template>

<script>
import {mapState} from 'vuex'

export default {
    props: {
        entries: {
            type: Array,
            required: true
        },
        title: {
            type: String,
            required: true
        }
    },
    computed: {
        ...mapState(['entry']),
        total() {
            return this.entries.reduce(function (a, b) {
                return a + b.amount_cents;
            }, 0);
        }
    }
}
</script>
