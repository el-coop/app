<style>
    .spa-loader {
        display: flex;
        height: 100%;
        justify-content: center;
        align-items: center;
    }

    .spa-loader__animation {
        color: #B86BFF;
        text-indent: -9999em;
        margin: 88px auto;
        position: relative;
        font-size: 11px;
        transform: translateZ(0);
        background: #B86BFF;
        animation: loader 1s infinite ease-in-out;
        animation-delay: -0.16s;
        width: 1em;
        height: 4em;
    }

    .spa-loader__animation:before,
    .spa-loader__animation:after {
        background: #B86BFF;
        animation: loader 1s infinite ease-in-out;
        width: 1em;
        height: 4em;

        position: absolute;
        top: 0;
        content: '';
    }

    .spa-loader__animation:before {
        left: -1.5em;
        animation-delay: -0.32s;
    }

    .spa-loader__animation:after {
        left: 1.5em;
    }


    @keyframes loader {
        0%,
        80%,
        100% {
            box-shadow: 0 0;
            height: 4em;
        }
        40% {
            box-shadow: 0 -2em;
            height: 5em;
        }
    }
</style>
