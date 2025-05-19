<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-6 center-screen">
                <div class="card shadow-lg p-4">
                    <form @submit.prevent="submitForm">
                        <div class="card-header text-center bg-info text-white">
                            <h4>Registration Form</h4>
                        </div>
                        <div class="card-body">
                            <!-- Name Field -->
                            <div class="form-group mb-3">
                                <label for="name" class="form-label">Name</label><input type="text" class="form-control"
                                    id="name" placeholder="Enter name" v-model="form.name"
                                    :class="{ 'is-invalid': form.errors.name }" />
                                <div v-if="form.errors.name" class="invalid-feedback">
                                    {{ form.errors.name }}
                                </div>
                            </div>
                            <!-- Email Field -->
                            <div class="form-group mb-3">
                                <label for="email" class="form-label">Email address</label><input type="email"
                                    class="form-control" id="email" placeholder="Enter email" v-model="form.email"
                                    :class="{ 'is-invalid': form.errors.email }" />
                                <div v-if="form.errors.email" class="invalid-feedback">
                                    {{ form.errors.email }}
                                </div>
                            </div>
                            <!-- Mobile Field -->
                            <div class="form-group mb-3">
                                <label for="mobile" class="form-label">Mobile</label><input type="tel"
                                    class="form-control" id="mobile" placeholder="Mobile" v-model="form.mobile"
                                    :class="{ 'is-invalid': form.errors.mobile }" />
                                <div v-if="form.errors.mobile" class="invalid-feedback">
                                    {{ form.errors.mobile }}
                                </div>
                            </div>
                            <!-- Password Field -->
                            <div class="form-group mb-3">
                                <label for="password" class="form-label">Password</label><input type="password"
                                    class="form-control" id="password" placeholder="Password" v-model="form.password"
                                    :class="{ 'is-invalid': form.errors.password }" />
                                <div v-if="form.errors.password" class="invalid-feedback">
                                    {{ form.errors.password }}
                                </div>
                                <small class="form-text text-muted">Password must be at least 8 characters and contain
                                    letters and
                                    numbers</small>
                            </div>
                            <!-- Confirm Password Field -->
                            <div class="form-group mb-3">
                                <label for="password_confirmation" class="form-label">Confirm Password</label><input
                                    type="password" class="form-control" id="password_confirmation"
                                    placeholder="Confirm Password" v-model="form.password_confirmation"
                                    :class="{ 'is-invalid': form.errors.password_confirmation }" />
                                <div v-if="form.errors.password_confirmation" class="invalid-feedback">
                                    {{ form.errors.password_confirmation }}
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-center">
                            <button type="submit" class="btn btn-success w-100 mb-2" :disabled="form.processing">
                                <span v-if="form.processing" class="spinner-border spinner-border-sm" role="status"
                                    aria-hidden="true"></span>{{ form.processing ? "Registering..." : "Register" }}
                            </button>
                            <Link href="/login" class="btn btn-link">Already have an account?</Link>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import { useToast } from "vue-toastification";
import { useForm, router, Link } from "@inertiajs/vue3";

const toast = useToast();

const form = useForm({
    name: "",
    email: "",
    mobile: "",
    password: "",
    password_confirmation: "",
});

const validateForm = () => {
    let isValid = true;
    form.clearErrors();

    // Name validation
    if (!form.name.trim()) {
        form.setError("name", "Name is required");
        isValid = false;
    } else if (!/^[a-zA-Z\s]+$/.test(form.name)) {
        form.setError("name", "Name can only contain letters and spaces");
        isValid = false;
    }

    // Email validation
    if (!form.email.trim()) {
        form.setError("email", "Email is required");
        isValid = false;
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.email)) {
        form.setError("email", "Invalid email format");
        isValid = false;
    }

    // Mobile validation
    if (!form.mobile.trim()) {
        form.setError("mobile", "Mobile number is required");
        isValid = false;
    } else if (!/^[0-9]{8,15}$/.test(form.mobile)) {
        form.setError("mobile", "Mobile must be 8–15 digits");
        isValid = false;
    }

    // Password validation
    if (!form.password) {
        form.setError("password", "Password is required");
        isValid = false;
    } else if (form.password.length < 8) {
        form.setError("password", "Password must be at least 8 characters");
        isValid = false;
    } else if (!/^(?=.*[A-Za-z])(?=.*\d).+$/.test(form.password)) {
        form.setError("password", "Password must contain letters and numbers");
        isValid = false;
    }

    // Confirm Password validation
    if (!form.password_confirmation) {
        form.setError("password_confirmation", "Please confirm your password");
        isValid = false;
    } else if (form.password_confirmation !== form.password) {
        form.setError("password_confirmation", "Passwords do not match");
        isValid = false;
    }

    return isValid;
};

const submitForm = () => {
    if (!validateForm()) {
        return; // Errors are already shown
    }

    form.post("/user-register", {
        preserveScroll: true,
        onSuccess: () => {
            toast.success("Registration successful! Please login.");
            router.visit("/login");
        },
        onError: (errors) => {
            if (errors.email) {
                form.setError("email", errors.email);
                toast.error("Email error: " + errors.email);
            }
            if (errors.mobile) {
                form.setError("mobile", errors.mobile);
                toast.error("Mobile error: " + errors.mobile);
            }
            if (errors.password) {
                form.setError("password", errors.password);
            }
        },
    });
};
</script>
<style scoped>
.center-screen {
    margin-top: 5vh;
    margin-bottom: 5vh;
}

.card {
    border-radius: 10px;
}

.invalid-feedback {
    display: block;
}

.btn:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}
</style>
