<template>
    <div class="ml-3 relative">
        <div>

            <button @click.prevent="open=!open"
                    aria-haspopup="true"
                    aria-label="User menu"
                    class="border-2 border-transparent hover:border-gray-200 max-w-xs flex items-center text-sm rounded-full text-white focus:outline-none focus:shadow-solid"
                    id="user-menu">

                <img :src="this.avatar"
                     alt=""
                     class="h-8 w-8 rounded-full">
            </button>
        </div>
        <div class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg" v-show="open">
            <div aria-labelledby="user-menu" aria-orientation="vertical" class="py-1 rounded-md bg-white shadow-xs"
                 role="menu">
                <a :href="this.logout_url"
                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                   onclick="event.preventDefault();document.getElementById('logout-form').submit();"
                   role="menuitem">Sign out</a>
                <form :action="this.logout_url" id="logout-form" method="POST" ref="form" style="display: none;">
                    <input :value="this.csrf_token" name="_token" type="hidden">
                </form>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        avatar: {
            type: String,
            required: true
        },
        logout_url: {
            type: String,
            required: true
        }

    },
    data() {
        return {
            csrf_token: Laravel.csrfToken || '',
            open: false
        }
    }
}
</script>
