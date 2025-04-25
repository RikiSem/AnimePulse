<template>
    <Head :title="new String('Профиль пользвателя ').concat(this.$attrs.auth.user.name)"></Head>
    <AuthenticatedLayout :user-id="this.$attrs.auth.user.id">
        <div class="py-12">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8">
                    <h2 class="text-lg font-medium text-gray-900">
                        Изменить аватар
                    </h2>
                    <avatar :src="this.user.avatar"></avatar>
                    <input @input="event => this.setAvatar(event)" type="file" name="avatar">
                    <p style="margin-top: 20px"><primary-button @click="saveAvatar">Сохранить</primary-button></p>
                    <notification :user-id="this.user.id"></notification>
                </div>
                <div
                    class="bg-white p-4 shadow sm:rounded-lg sm:p-8"
                >
                    <UpdateProfileInformationForm
                        :must-verify-email="mustVerifyEmail"
                        :status="status"
                        class="max-w-xl"
                    />
                </div>

                <div
                    class="bg-white p-4 shadow sm:rounded-lg sm:p-8"
                >
                    <UpdatePasswordForm class="max-w-xl" />
                </div>

                <div
                    class="bg-white p-4 shadow sm:rounded-lg sm:p-8"
                >
                    <DeleteUserForm class="max-w-xl" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';
import Btn from "@/Components/Btn.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Notification from "@/Components/Notification.vue";
import Avatar from "@/Components/Avatar.vue";

export default {
    components: {
        Avatar,
        Head,
        Notification,
        PrimaryButton,
        Btn, UpdateProfileInformationForm, UpdatePasswordForm, DeleteUserForm, AuthenticatedLayout},
    data(){
        return {
            user: Object,
            newAvatar: null,
        }
    },
    props:{
        mustVerifyEmail: {
            type: Boolean,
        },
        status: {
            type: String,
        },
    },
    mounted() {
        this.getUserData()
    },
    methods:{
        async saveAvatar(){
            let data = new FormData();
            data.set('avatar', this.newAvatar)
            data.set('userId', this.user.id)
            await (axios.post(route('user.save-avatar'), data))
        },
        setAvatar(event){
            let render = new FileReader()
            render.onload = (e) => {
                this.user.avatar = e.target.result;
            };

            this.newAvatar = event.target.files[0]
            render.readAsDataURL(event.target.files[0])
        },
        async getUserData(){
            let result = await (axios.post(route('user.get'), {
                id: this.$attrs.auth.user.id
            }))
            this.user = result.data
        }
    }
}
</script>
<style scoped>
img{
    width: 20rem;
}
</style>
