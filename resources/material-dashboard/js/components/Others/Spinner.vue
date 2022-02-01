<template>
    <div class="spinner-wrap">
        <div class="spinner"></div>
    </div>
</template>

<script>
export default {
    name: "Spinner"
}
</script>

<style scoped>

@keyframes spin {
    0% {transform: rotate(0deg);}
    100% {transform: rotate(360deg);}
}

.spinner-wrap {
    position: fixed;
    left: 0;
    right: 0;
    top: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 199;
}

.spinner {
    position: absolute;
    height: 60px;
    width: 60px;
    border: 3px solid transparent;
    border-top-color: #A04668;
    top: 50%;
    left: 50%;
    margin: -30px;
    border-radius: 50%;
    animation: spin 2s linear infinite;
}

.spinner::before, .spinner::after {
    content:'';
    position: absolute;
    border: 3px solid transparent;
    border-radius: 50%;
}

.spinner::before{
    border-top-color: #254E70;
    top: -12px;
    left: -12px;
    right: -12px;
    bottom: -12px;
    animation: spin 3s linear infinite;
}

.spinner::after{
    border-top-color: #FFFBFE;
    top: 6px;
    left: 6px;
    right: 6px;
    bottom: 6px;
    animation: spin 4s linear infinite;
}
</style>
