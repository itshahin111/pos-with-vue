<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-6 center-screen">
                <div class="card shadow-lg p-4">
                    <form @submit.prevent="submit">
                        <div class="card-header text-center bg-info text-white">
                            <h4>ENTER OTP CODE</h4>
                        </div>
                        <div class="card-body">
                            <label>6 Digit Code Here</label>
                            <input id="otp" v-model="form.otp" placeholder="Code" class="form-control" type="text" />
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
import { useForm } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';

const toast = useToast();
const form = useForm({ otp: '' });

function submit() {
    form.post("/verify-otp-code", {
        onSuccess: (page) => {
            if (page.props.flash.status) {
                toast.success(page.props.flash.message);
                router.visit("/reset-password");
            } else {
                toast.error(page.props.flash.message);;
            }
        },
        onError: (errors) => {
    toast.error(errors.otp ? errors.otp[0] : "Something went wrong");
}
    });
}
</script>
