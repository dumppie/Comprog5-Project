/**
 * Client-side validation utilities
 * Mirrors server-side validation rules for immediate feedback
 */

class FormValidator {
    constructor(form) {
        this.form = form;
        this.rules = {};
        this.messages = {};
        this.init();
    }

    init() {
        this.form.addEventListener('submit', (e) => {
            if (!this.validate()) {
                e.preventDefault();
                this.showFirstError();
            }
        });

        // Add real-time validation
        this.form.addEventListener('input', (e) => {
            this.validateField(e.target);
        });

        this.form.addEventListener('blur', (e) => {
            this.validateField(e.target);
        });
    }

    addRule(fieldName, rules, messages = {}) {
        this.rules[fieldName] = rules;
        this.messages[fieldName] = messages;
    }

    validate() {
        let isValid = true;
        const fields = this.form.querySelectorAll('input, select, textarea');

        fields.forEach(field => {
            if (!this.validateField(field)) {
                isValid = false;
            }
        });

        return isValid;
    }

    validateField(field) {
        const fieldName = field.name;
        const value = field.value.trim();
        const rules = this.rules[fieldName];

        if (!rules) return true;

        let isValid = true;
        let errorMessage = '';

        for (const rule of rules) {
            const result = this.applyRule(rule, value, field);
            if (!result.valid) {
                isValid = false;
                errorMessage = result.message;
                break;
            }
        }

        this.showFieldError(field, isValid, errorMessage);
        return isValid;
    }

    applyRule(rule, value, field) {
        // Required validation
        if (rule.required && !value) {
            return {
                valid: false,
                message: this.messages[field.name]?.required || 'This field is required.'
            };
        }

        // Skip other validations if field is empty and not required
        if (!value && !rule.required) {
            return { valid: true };
        }

        // Email validation
        if (rule.email && !this.isValidEmail(value)) {
            return {
                valid: false,
                message: this.messages[field.name]?.email || 'Please enter a valid email address.'
            };
        }

        // Numeric validation
        if (rule.numeric && !this.isNumeric(value)) {
            return {
                valid: false,
                message: this.messages[field.name]?.numeric || 'This field must be a number.'
            };
        }

        // Integer validation
        if (rule.integer && !this.isInteger(value)) {
            return {
                valid: false,
                message: this.messages[field.name]?.integer || 'This field must be a whole number.'
            };
        }

        // Min/Max value validation
        if (rule.min !== undefined && parseFloat(value) < rule.min) {
            return {
                valid: false,
                message: this.messages[field.name]?.min || `Value must be at least ${rule.min}.`
            };
        }

        if (rule.max !== undefined && parseFloat(value) > rule.max) {
            return {
                valid: false,
                message: this.messages[field.name]?.max || `Value must not exceed ${rule.max}.`
            };
        }

        // Min/Max length validation
        if (rule.minLength && value.length < rule.minLength) {
            return {
                valid: false,
                message: this.messages[field.name]?.minLength || `Must be at least ${rule.minLength} characters.`
            };
        }

        if (rule.maxLength && value.length > rule.maxLength) {
            return {
                valid: false,
                message: this.messages[field.name]?.maxLength || `Must not exceed ${rule.maxLength} characters.`
            };
        }

        // Password strength validation
        if (rule.password) {
            const passwordResult = this.validatePassword(value);
            if (!passwordResult.valid) {
                return {
                    valid: false,
                    message: passwordResult.message
                };
            }
        }

        // Password confirmation
        if (rule.confirmed) {
            const passwordField = this.form.querySelector('input[name="password"]');
            const confirmPasswordField = this.form.querySelector('input[name="password_confirmation"]');
            
            if (passwordField && confirmPasswordField && passwordField.value !== confirmPasswordField.value) {
                return {
                    valid: false,
                    message: this.messages[field.name]?.confirmed || 'Password confirmation does not match.'
                };
            }
        }

        // File validation
        if (rule.file && field.files.length > 0) {
            const file = field.files[0];
            
            // File type validation
            if (rule.mimes && !this.isValidFileType(file, rule.mimes)) {
                return {
                    valid: false,
                    message: this.messages[field.name]?.mimes || `File must be of type: ${rule.mimes.join(', ')}.`
                };
            }

            // File size validation (in KB)
            if (rule.maxSize && file.size > rule.maxSize * 1024) {
                return {
                    valid: false,
                    message: this.messages[field.name]?.maxSize || `File size must not exceed ${rule.maxSize}KB.`
                };
            }
        }

        return { valid: true };
    }

    validatePassword(password) {
        const requirements = {
            minLength: password.length >= 8,
            hasUppercase: /[A-Z]/.test(password),
            hasLowercase: /[a-z]/.test(password),
            hasNumbers: /\d/.test(password)
        };

        const missingRequirements = [];
        if (!requirements.minLength) missingRequirements.push('at least 8 characters');
        if (!requirements.hasUppercase) missingRequirements.push('1 uppercase letter');
        if (!requirements.hasLowercase) missingRequirements.push('1 lowercase letter');
        if (!requirements.hasNumbers) missingRequirements.push('1 number');

        if (missingRequirements.length > 0) {
            return {
                valid: false,
                message: `Password must contain ${missingRequirements.join(', ')}.`
            };
        }

        return { valid: true };
    }

    isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    isNumeric(value) {
        return !isNaN(value) && value.trim() !== '';
    }

    isInteger(value) {
        return Number.isInteger(parseFloat(value));
    }

    isValidFileType(file, allowedTypes) {
        const extension = file.name.split('.').pop().toLowerCase();
        return allowedTypes.includes(extension);
    }

    showFieldError(field, isValid, message = '') {
        const formGroup = field.closest('.mb-3, .form-group') || field.parentElement;
        let errorElement = formGroup.querySelector('.invalid-feedback, .text-danger');

        if (!isValid) {
            field.classList.add('is-invalid');
            field.classList.remove('is-valid');

            if (!errorElement) {
                errorElement = document.createElement('div');
                errorElement.className = 'invalid-feedback';
                formGroup.appendChild(errorElement);
            }

            errorElement.textContent = message;
            errorElement.style.display = 'block';
        } else {
            field.classList.remove('is-invalid');
            field.classList.add('is-valid');

            if (errorElement) {
                errorElement.style.display = 'none';
            }
        }
    }

    showFirstError() {
        const firstInvalid = this.form.querySelector('.is-invalid');
        if (firstInvalid) {
            firstInvalid.focus();
            firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }

    // Static method to setup validation for common forms
    static setupProductForm(form) {
        const validator = new FormValidator(form);

        validator.addRule('name', [
            { required: true },
            { maxLength: 255 }
        ], {
            required: 'Product name is required.',
            maxLength: 'Product name may not be greater than 255 characters.'
        });

        validator.addRule('category', [
            { required: true }
        ], {
            required: 'Product category is required.'
        });

        validator.addRule('price', [
            { required: true },
            { numeric: true },
            { min: 0 },
            { max: 999999.99 }
        ], {
            required: 'Product price is required.',
            numeric: 'Product price must be a number.',
            min: 'Product price must be at least 0.',
            max: 'Product price must not exceed 999,999.99.'
        });

        validator.addRule('stock_quantity', [
            { required: true },
            { integer: true },
            { min: 0 },
            { max: 999999 }
        ], {
            required: 'Stock quantity is required.',
            integer: 'Stock quantity must be a whole number.',
            min: 'Stock quantity must be at least 0.',
            max: 'Stock quantity must not exceed 999,999.'
        });

        validator.addRule('thumbnail_photo', [
            { file: true },
            { mimes: ['jpeg', 'jpg', 'png'] },
            { maxSize: 2048 } // 2MB
        ], {
            mimes: 'Thumbnail must be a JPEG or PNG file.',
            maxSize: 'Thumbnail may not be greater than 2MB.'
        });

        return validator;
    }

    static setupRegistrationForm(form) {
        const validator = new FormValidator(form);

        validator.addRule('first_name', [
            { required: true },
            { maxLength: 255 }
        ], {
            required: 'First name is required.',
            maxLength: 'First name may not be greater than 255 characters.'
        });

        validator.addRule('last_name', [
            { required: true },
            { maxLength: 255 }
        ], {
            required: 'Last name is required.',
            maxLength: 'Last name may not be greater than 255 characters.'
        });

        validator.addRule('email', [
            { required: true },
            { email: true },
            { maxLength: 255 }
        ], {
            required: 'Email address is required.',
            email: 'Please enter a valid email address.',
            maxLength: 'Email may not be greater than 255 characters.'
        });

        validator.addRule('password', [
            { required: true },
            { password: true },
            { confirmed: true }
        ], {
            required: 'Password is required.',
            confirmed: 'Password confirmation does not match.'
        });

        validator.addRule('password_confirmation', [
            { required: true }
        ], {
            required: 'Password confirmation is required.'
        });

        validator.addRule('profile_photo', [
            { file: true },
            { mimes: ['jpeg', 'jpg', 'png', 'gif'] },
            { maxSize: 2048 } // 2MB
        ], {
            mimes: 'Profile photo must be JPEG, PNG, or GIF file.',
            maxSize: 'Profile photo may not be greater than 2MB.'
        });

        return validator;
    }
}

// Auto-initialize validation for common forms
document.addEventListener('DOMContentLoaded', function() {
    // Product forms
    const productForms = document.querySelectorAll('form[action*="products"]');
    productForms.forEach(form => {
        FormValidator.setupProductForm(form);
    });

    // Registration form
    const registrationForm = document.querySelector('form[action*="register"]');
    if (registrationForm) {
        FormValidator.setupRegistrationForm(registrationForm);
    }
});
