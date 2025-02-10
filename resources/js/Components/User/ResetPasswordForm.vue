<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-6 center-screen">
                <div class="card shadow-lg p-4">
                    <form @submit.prevent="submit">
                        <div class="card-header text-center bg-info text-white">
                            <h4>SET NEW PASSWORD</h4>
                        </div>
                        <div class="card-body">
                            <label>New Password</label>
                            <input id="password" v-model="form.password" placeholder="New Password" class="form-control" type="password" />
                            <br />
                            <label>Confirm Password</label>
                            <input id="password_confirmation" v-model="form.password_confirmation" placeholder="Confirm Password" class="form-control" type="password" />
                            <br />
                            <button type="submit" class="btn w-100 btn-success" :disabled="form.processing">Next</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useToast } from 'vue-toastification';
import { useForm, router } from '@inertiajs/vue3';

const toast = useToast();
const form = useForm({ password: '', password_confirmation: '' });

function submit() {
    form.post("/reset-password", {
        onSuccess: (page) => {
            if (page.props.flash.status) {
                toast.success(page.props.flash.message);
                router.visit("/login");
            } else {
                toast.error(page.props.flash.message);
            }
        }
    });
}
</script>
