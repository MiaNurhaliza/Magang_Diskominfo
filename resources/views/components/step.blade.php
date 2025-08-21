@props(['icon', 'title', 'desc'])

<style>
    @media (max-width: 768px) {
        .step-box {
            margin-bottom: 1.5rem;
        }
        .step-icon {
            width: 60px !important;
        }
        .step-title {
            font-size: 1.1rem !important;
        }
        .step-desc {
            font-size: 0.9rem;
            padding: 0 1rem;
        }
    }
    @media (max-width: 576px) {
        .step-box {
            margin-bottom: 1rem;
        }
        .step-icon {
            width: 50px !important;
        }
        .step-title {
            font-size: 1rem !important;
        }
        .step-desc {
            font-size: 0.85rem;
            padding: 0 0.5rem;
        }
    }
</style>

<div class="d-flex flex-column align-items-center text-center">
    <div class="step-box">
        <img src="{{ asset('images/' . $icon) }}" class="step-icon" alt="{{ $title }}">
    </div>
    <div class="step-title mt-2">{{ $title }}</div>
    <div class="step-desc">{{ $desc }}</div>
</div>
