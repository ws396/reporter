<template>
    <div class="mb-4 text-sm text-gray-600">
        Благодарим за регистрацию. Прежде чем начать, пожалуйста, подвердите свой электронный адрес, кликнув на ссылку, высланную вам на него.
        Если вы не получили ссылку, то вы можете выслать ещё одну.
    </div>

    <div class="mb-4 font-medium text-sm text-green-600" v-if="verificationLinkSent" >
        Новая ссылка для подтверждения была выслана на указанный электронный адрес.
    </div>

    <form @submit.prevent="submit">
        <div class="mt-4 flex items-center justify-between">
            <breeze-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Перевыслать ссылку
            </breeze-button>

            <inertia-link :href="route('logout')" method="post" as="button" class="underline text-sm text-gray-600 hover:text-gray-900">Выйти
            </inertia-link>
        </div>
    </form>
</template>

<script>
    import BreezeButton from '@/Components/Button'
    import BreezeGuestLayout from "@/Layouts/Guest"

    export default {
        layout: BreezeGuestLayout,

        components: {
            BreezeButton,
        },

        props: {
            status: String,
        },

        data() {
            return {
                form: this.$inertia.form()
            }
        },

        methods: {
            submit() {
                this.form.post(this.route('verification.send'))
            },
        },

        computed: {
            verificationLinkSent() {
                return this.status === 'verification-link-sent';
            }
        }
    }
</script>
