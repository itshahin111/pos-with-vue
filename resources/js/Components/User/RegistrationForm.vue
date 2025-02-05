<template>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-5">
                <div class="card shadow-lg p-4">
                    <form @submit.prevent="submitForm">
                        <div class="card-header text-center bg-info text-white">
                            <h4>Registration Form</h4>
                        </div>
                        <div class="card-body">
                            <div class="form-group mb-3">
                                <label for="name" class="form-label"
                                    >Name</label
                                >
                                <input
                                    type="text"
                                    class="form-control"
                                    id="name"
                                    placeholder="Enter name"
                                    v-model="form.name"
                                />

                            </div>
                            <div class="form-group mb-3">
                                <label for="email" class="form-label"
                                    >Email address</label
                                >
                                <input
                                    type="email"
                                    class="form-control"
                                    id="email"
                                    placeholder="Enter email"
                                    v-model="form.email"
                                />

                            </div>
                            <div class="form-group mb-3">
                                <label for="mobile" class="form-label"
                                    >Mobile</label
                                >
                                <input
                                    type="text"
                                    class="form-control"
                                    id="mobile"
                                    placeholder="Mobile"
                                    v-model="form.mobile"
                                />

                            </div>
                            <div class="form-group mb-3">
                                <label for="password" class="form-label"
                                    >Password</label
                                >
                                <input
                                    type="password"
                                    class="form-control"
                                    id="password"
                                    placeholder="Password"
                                    v-model="form.password"
                                />

                            </div>
                        </div>
                        <div class="card-footer text-center">
                            <button
                                type="submit"
                                class="btn btn-success w-100 mb-2"
                            >
                                Register
                            </button>
                            <Link href="/login" class="btn btn-link">
                                Already have an account?
                            </Link>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useToast } from "vue-toastification";
import { useForm, router, usePage, Link} from "@inertiajs/vue3";

const toast = useToast();

const form = useForm({
    name: "",
    email: "",
    mobile: "",
    password: "",
});

function submitForm() {
    if (!form.name.trim()) {
        toast.error("Name is required"); // ✅ Should now display error
        return;
    }
    if (!form.email.trim()) {
        toast.error("Email is required");
        return;
    }
    if (!form.mobile.trim()) {
        toast.error("Mobile is required");
        return;
    }
    if (!form.password.trim()) {
        toast.error("Password is required");
        return;
    }

    form.post("/user-register", {
        onSuccess: () => {
            toast.success("User Registered Successfully!");
            router.get("/login");
        },
        onError: (errors) => {
            console.log(errors); // Debugging
            toast.error("Registration failed. Check the fields again.");
        }
    });
}
</script>
