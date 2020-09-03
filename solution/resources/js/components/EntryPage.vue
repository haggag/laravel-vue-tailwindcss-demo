<template>
    <div>
        <div v-if="loaded">
            <notification/>
            <template v-if="noEntries">
                <p class="text-center italic">You don't have any entries!</p>
            </template>
            <template v-else>
                <entry-card-list :entries="entry.entries[key]" :key="key" :title="key" @editing="handleEditing"
                                 v-for="key in sortedKeys"/>
                <paginator/>
            </template>
        </div>
        <div class="mt-6" v-else>
            <ContentLoader>
                <rect height="10" rx="3" ry="3" width="80" x="0" y="0"/>
                <rect height="10" rx="3" ry="3" width="220" x="10" y="20"/>
                <rect height="10" rx="3" ry="3" width="170" x="10" y="40"/>
                <rect height="10" rx="3" ry="3" width="220" x="10" y="70"/>
                <rect height="10" rx="3" ry="3" width="170" x="10" y="90"/>
            </ContentLoader>
            <ContentLoader>
                <rect height="10" rx="3" ry="3" width="80" x="0" y="0"/>
                <rect height="10" rx="3" ry="3" width="220" x="10" y="20"/>
                <rect height="10" rx="3" ry="3" width="170" x="10" y="40"/>
            </ContentLoader>
        </div>
    </div>
</template>

<script>
import {ContentLoader,} from 'vue-content-loader'
import {mapState} from 'vuex'
import store from '../store/store'

function getPageEntries($this) {
    const currentPage = 1;

    store
        .dispatch('entry/fetchEntries', {
            page: currentPage
        })
        .then(() => {
            $this.$data.loaded = true
        })
}

export default {
    components: {
        ContentLoader
    },
    data() {
        return {
            editedCard: null,
            loaded: false
        }
    },
    mounted() {
        const $this = this
        getPageEntries($this)
    },
    methods: {
        handleEditing(newEditedCard) {
            console.log("handling editing event ")
            if (newEditedCard !== this.editedCard) {
                if (this.editedCard) {
                    this.editedCard.endEditing()
                }
                this.editedCard = newEditedCard
            }
        }
    },
    computed: {
        ...mapState(['entry']),
        sortedKeys() {
            return this.$store.getters['entry/sortedKeys']
        },
        noEntries() {
            return this.$store.getters['entry/empty']
        }
    }
}
</script>
