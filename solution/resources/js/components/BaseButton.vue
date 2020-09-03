<template>
    <div>
        <button :class="dynamic_class" :disabled="disable"
                class="sm:ml-4 shadow-sm  inline-flex items-center px-2 py-2 border border-transparent text-xs leading-5  rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:shadow-outline-blue focus:border-blue-700 active:bg-blue-700 transition duration-150 ease-in-out"
                v-bind="$attrs"
                v-on="$listeners">
            <slot/>
        </button>
    </div>
</template>

<script>
export default {
    inheritAttrs: false,
    props: {
        disable: {
            type: Boolean,
            default: false
        },
    },
    computed: {
        dynamic_class() {
            let classes = ''
            if (this.disable) {
                classes = "opacity-50 cursor-not-allowed hover:bg-blue-600"
            }
            return classes
        }
    }
}
</script>
