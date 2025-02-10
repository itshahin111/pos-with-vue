<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-6 center-screen">
                <div class="card shadow-lg p-4">
                    <form @submit.prevent="submit">
                        <div class="card-header text-center bg-info text-white">
                            <h4>SEND OTP</h4>
                        </div>
                        <div class="card-body">
                            <label>Your email address</label>
                            <input
                                id="email"
                                v-model="form.email"
                                placeholder="User Email"
                                class="form-control"
                                type="email"
                                required
                            />
                            <br />
                            <button type="submit" class="btn w-100 btn-success" :disabled="form.processing">
                                Next
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useToast } from "vue-toastification";
import { useForm, router } from "@inertiajs/vue3";

const toast = useToast();
const form = useForm({ email: "" });

function submit() {
    form.post("/send-otp", {
        onSuccess: (page) => {
            if (page.props.flash.status) {
                toast.success(page.props.flash.message);
                router.visit("/verify-otp");
            } else {
                toast.error(page.props.flash.error);;
            }
        },
        onError: (errors) => {
            toast.error(errors.email ? errors.email[0] : "Something went wrong");
        }
    });
}
</script>
