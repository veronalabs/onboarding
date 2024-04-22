<?php

use Veronalabs\Onboarding\Wizard;

?>
<div class="onboarding-wrap">
    <?php if (isset($config['css_url']) &&  $config['css_url'] != "") {
        echo '<link href="' . $config['css_url'] . '" rel="stylesheet" />';
        } else {
            echo '<link href="' . plugin_dir_url(__FILE__) . '/assets/main.min.css" rel="stylesheet" />';
        }
    ?>
    <body class="wpsms-onboarding">
        <div id="main" role="content">

            <section class="c-section--logo u-text-center">
                <svg width="126" height="21" viewBox="0 0 126 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M123.654 5.43359H125.793V15.707C125.793 16.6576 125.591 17.4648 125.188 18.1289C124.784 18.793 124.221 19.2975 123.498 19.6426C122.775 19.9941 121.939 20.1699 120.988 20.1699C120.585 20.1699 120.135 20.1113 119.641 19.9941C119.152 19.877 118.677 19.6882 118.215 19.4277C117.759 19.1738 117.378 18.8385 117.072 18.4219L118.176 17.0352C118.553 17.4844 118.97 17.8132 119.426 18.0215C119.882 18.2298 120.36 18.334 120.861 18.334C121.402 18.334 121.861 18.2331 122.238 18.0312C122.622 17.8359 122.919 17.5462 123.127 17.1621C123.335 16.778 123.439 16.3092 123.439 15.7559V7.82617L123.654 5.43359ZM116.477 10.834V10.6289C116.477 9.82812 116.574 9.09896 116.77 8.44141C116.965 7.77734 117.245 7.20768 117.609 6.73242C117.974 6.25065 118.417 5.88281 118.938 5.62891C119.458 5.36849 120.048 5.23828 120.705 5.23828C121.389 5.23828 121.971 5.36198 122.453 5.60938C122.941 5.85677 123.348 6.21159 123.674 6.67383C123.999 7.12956 124.253 7.67643 124.436 8.31445C124.624 8.94596 124.764 9.64909 124.855 10.4238V11.0781C124.771 11.8333 124.628 12.5234 124.426 13.1484C124.224 13.7734 123.957 14.3138 123.625 14.7695C123.293 15.2253 122.883 15.5768 122.395 15.8242C121.913 16.0716 121.343 16.1953 120.686 16.1953C120.041 16.1953 119.458 16.0618 118.938 15.7949C118.423 15.528 117.98 15.1536 117.609 14.6719C117.245 14.1901 116.965 13.6237 116.77 12.9727C116.574 12.3151 116.477 11.6022 116.477 10.834ZM118.83 10.6289V10.834C118.83 11.3158 118.876 11.765 118.967 12.1816C119.064 12.5983 119.211 12.9661 119.406 13.2852C119.608 13.5977 119.862 13.8451 120.168 14.0273C120.48 14.2031 120.848 14.291 121.271 14.291C121.825 14.291 122.277 14.1738 122.629 13.9395C122.987 13.7051 123.26 13.3893 123.449 12.9922C123.645 12.5885 123.781 12.1393 123.859 11.6445V9.87695C123.82 9.49284 123.739 9.13477 123.615 8.80273C123.498 8.4707 123.339 8.18099 123.137 7.93359C122.935 7.67969 122.681 7.48438 122.375 7.34766C122.069 7.20443 121.708 7.13281 121.291 7.13281C120.868 7.13281 120.5 7.22396 120.188 7.40625C119.875 7.58854 119.618 7.83919 119.416 8.1582C119.221 8.47721 119.074 8.84831 118.977 9.27148C118.879 9.69466 118.83 10.1471 118.83 10.6289Z" fill="#C24225" />
                    <path d="M108.088 7.68945V16H105.734V5.43359H107.951L108.088 7.68945ZM107.668 10.3262L106.906 10.3164C106.913 9.56771 107.017 8.88086 107.219 8.25586C107.427 7.63086 107.714 7.09375 108.078 6.64453C108.449 6.19531 108.892 5.85026 109.406 5.60938C109.921 5.36198 110.493 5.23828 111.125 5.23828C111.633 5.23828 112.092 5.3099 112.502 5.45312C112.919 5.58984 113.273 5.81445 113.566 6.12695C113.866 6.43945 114.094 6.84635 114.25 7.34766C114.406 7.84245 114.484 8.45117 114.484 9.17383V16H112.121V9.16406C112.121 8.65625 112.046 8.25586 111.896 7.96289C111.753 7.66341 111.542 7.45182 111.262 7.32812C110.988 7.19792 110.646 7.13281 110.236 7.13281C109.833 7.13281 109.471 7.21745 109.152 7.38672C108.833 7.55599 108.563 7.78711 108.342 8.08008C108.127 8.37305 107.961 8.71159 107.844 9.0957C107.727 9.47982 107.668 9.88997 107.668 10.3262Z" fill="#C24225" />
                    <path d="M103.176 5.43359V16H100.812V5.43359H103.176ZM100.656 2.66016C100.656 2.30208 100.773 2.00586 101.008 1.77148C101.249 1.5306 101.581 1.41016 102.004 1.41016C102.421 1.41016 102.749 1.5306 102.99 1.77148C103.231 2.00586 103.352 2.30208 103.352 2.66016C103.352 3.01172 103.231 3.30469 102.99 3.53906C102.749 3.77344 102.421 3.89062 102.004 3.89062C101.581 3.89062 101.249 3.77344 101.008 3.53906C100.773 3.30469 100.656 3.01172 100.656 2.66016Z" fill="#C24225" />
                    <path d="M95.832 13.8125V1H98.1953V16H96.0566L95.832 13.8125ZM88.957 10.834V10.6289C88.957 9.82812 89.0514 9.09896 89.2402 8.44141C89.429 7.77734 89.7025 7.20768 90.0605 6.73242C90.4186 6.25065 90.8548 5.88281 91.3691 5.62891C91.8835 5.36849 92.4629 5.23828 93.1074 5.23828C93.7454 5.23828 94.3053 5.36198 94.7871 5.60938C95.2689 5.85677 95.679 6.21159 96.0176 6.67383C96.3561 7.12956 96.6263 7.67643 96.8281 8.31445C97.0299 8.94596 97.1732 9.64909 97.2578 10.4238V11.0781C97.1732 11.8333 97.0299 12.5234 96.8281 13.1484C96.6263 13.7734 96.3561 14.3138 96.0176 14.7695C95.679 15.2253 95.2656 15.5768 94.7773 15.8242C94.2956 16.0716 93.7324 16.1953 93.0879 16.1953C92.4499 16.1953 91.8737 16.0618 91.3594 15.7949C90.8516 15.528 90.4186 15.1536 90.0605 14.6719C89.7025 14.1901 89.429 13.6237 89.2402 12.9727C89.0514 12.3151 88.957 11.6022 88.957 10.834ZM91.3105 10.6289V10.834C91.3105 11.3158 91.3529 11.765 91.4375 12.1816C91.5286 12.5983 91.6686 12.9661 91.8574 13.2852C92.0462 13.5977 92.2904 13.8451 92.5898 14.0273C92.8958 14.2031 93.2604 14.291 93.6836 14.291C94.2174 14.291 94.6569 14.1738 95.002 13.9395C95.347 13.7051 95.6172 13.3893 95.8125 12.9922C96.0143 12.5885 96.151 12.1393 96.2227 11.6445V9.87695C96.1836 9.49284 96.1022 9.13477 95.9785 8.80273C95.8613 8.4707 95.7018 8.18099 95.5 7.93359C95.2982 7.67969 95.0475 7.48438 94.748 7.34766C94.4551 7.20443 94.1068 7.13281 93.7031 7.13281C93.2734 7.13281 92.9089 7.22396 92.6094 7.40625C92.3099 7.58854 92.0625 7.83919 91.8672 8.1582C91.6784 8.47721 91.5384 8.84831 91.4473 9.27148C91.3561 9.69466 91.3105 10.1471 91.3105 10.6289Z" fill="#C24225" />
                    <path d="M84.875 7.44531V16H82.5215V5.43359H84.7676L84.875 7.44531ZM88.1074 5.36523L88.0879 7.55273C87.9447 7.52669 87.7884 7.50716 87.6191 7.49414C87.4564 7.48112 87.2936 7.47461 87.1309 7.47461C86.7272 7.47461 86.3724 7.5332 86.0664 7.65039C85.7604 7.76107 85.5033 7.92383 85.2949 8.13867C85.0931 8.34701 84.9368 8.60091 84.8262 8.90039C84.7155 9.19987 84.6504 9.53516 84.6309 9.90625L84.0938 9.94531C84.0938 9.28125 84.1589 8.66602 84.2891 8.09961C84.4193 7.5332 84.6146 7.03516 84.875 6.60547C85.1419 6.17578 85.474 5.84049 85.8711 5.59961C86.2747 5.35872 86.7402 5.23828 87.2676 5.23828C87.4108 5.23828 87.5638 5.2513 87.7266 5.27734C87.8958 5.30339 88.0228 5.33268 88.1074 5.36523Z" fill="#C24225" />
                    <path d="M77.6875 13.8809V8.8418C77.6875 8.46419 77.6191 8.13867 77.4824 7.86523C77.3457 7.5918 77.1374 7.38021 76.8574 7.23047C76.584 7.08073 76.2389 7.00586 75.8223 7.00586C75.4382 7.00586 75.1061 7.07096 74.8262 7.20117C74.5462 7.33138 74.3281 7.50716 74.1719 7.72852C74.0156 7.94987 73.9375 8.20052 73.9375 8.48047H71.5938C71.5938 8.0638 71.6947 7.66016 71.8965 7.26953C72.0983 6.87891 72.3913 6.5306 72.7754 6.22461C73.1595 5.91862 73.6185 5.67773 74.1523 5.50195C74.6862 5.32617 75.2852 5.23828 75.9492 5.23828C76.7435 5.23828 77.4466 5.37174 78.0586 5.63867C78.6771 5.9056 79.1621 6.30924 79.5137 6.84961C79.8717 7.38346 80.0508 8.05404 80.0508 8.86133V13.5586C80.0508 14.0404 80.0833 14.4733 80.1484 14.8574C80.2201 15.235 80.321 15.5638 80.4512 15.8438V16H78.0391C77.9284 15.7461 77.8405 15.4238 77.7754 15.0332C77.7168 14.6361 77.6875 14.252 77.6875 13.8809ZM78.0293 9.57422L78.0488 11.0293H76.3594C75.9232 11.0293 75.5391 11.0716 75.207 11.1562C74.875 11.2344 74.5983 11.3516 74.377 11.5078C74.1556 11.6641 73.9896 11.8529 73.8789 12.0742C73.7682 12.2956 73.7129 12.5462 73.7129 12.8262C73.7129 13.1061 73.778 13.3633 73.9082 13.5977C74.0384 13.8255 74.2272 14.0046 74.4746 14.1348C74.7285 14.265 75.0345 14.3301 75.3926 14.3301C75.8743 14.3301 76.2943 14.2324 76.6523 14.0371C77.0169 13.8353 77.3034 13.5911 77.5117 13.3047C77.7201 13.0117 77.8307 12.735 77.8438 12.4746L78.6055 13.5195C78.5273 13.7865 78.3939 14.0729 78.2051 14.3789C78.0163 14.6849 77.7689 14.9779 77.4629 15.2578C77.1634 15.5312 76.8021 15.7559 76.3789 15.9316C75.9622 16.1074 75.4805 16.1953 74.9336 16.1953C74.2435 16.1953 73.6283 16.0586 73.0879 15.7852C72.5475 15.5052 72.1243 15.1309 71.8184 14.6621C71.5124 14.1868 71.3594 13.6497 71.3594 13.0508C71.3594 12.4909 71.4635 11.9961 71.6719 11.5664C71.8867 11.1302 72.1992 10.7656 72.6094 10.4727C73.026 10.1797 73.5339 9.95833 74.1328 9.80859C74.7318 9.65234 75.4154 9.57422 76.1836 9.57422H78.0293Z" fill="#C24225" />
                    <path d="M59.9336 10.834V10.6094C59.9336 9.84766 60.0443 9.14128 60.2656 8.49023C60.487 7.83268 60.806 7.26302 61.2227 6.78125C61.6458 6.29297 62.1602 5.91536 62.7656 5.64844C63.3776 5.375 64.0677 5.23828 64.8359 5.23828C65.6107 5.23828 66.3008 5.375 66.9062 5.64844C67.5182 5.91536 68.0358 6.29297 68.459 6.78125C68.8822 7.26302 69.2044 7.83268 69.4258 8.49023C69.6471 9.14128 69.7578 9.84766 69.7578 10.6094V10.834C69.7578 11.5957 69.6471 12.3021 69.4258 12.9531C69.2044 13.6042 68.8822 14.1738 68.459 14.6621C68.0358 15.1439 67.5215 15.5215 66.916 15.7949C66.3105 16.0618 65.6237 16.1953 64.8555 16.1953C64.0807 16.1953 63.3874 16.0618 62.7754 15.7949C62.1699 15.5215 61.6556 15.1439 61.2324 14.6621C60.8092 14.1738 60.487 13.6042 60.2656 12.9531C60.0443 12.3021 59.9336 11.5957 59.9336 10.834ZM62.2871 10.6094V10.834C62.2871 11.3092 62.3359 11.7585 62.4336 12.1816C62.5312 12.6048 62.6842 12.9759 62.8926 13.2949C63.1009 13.6139 63.3678 13.8646 63.6934 14.0469C64.0189 14.2292 64.4062 14.3203 64.8555 14.3203C65.2917 14.3203 65.6693 14.2292 65.9883 14.0469C66.3138 13.8646 66.5807 13.6139 66.7891 13.2949C66.9974 12.9759 67.1504 12.6048 67.248 12.1816C67.3522 11.7585 67.4043 11.3092 67.4043 10.834V10.6094C67.4043 10.1406 67.3522 9.69792 67.248 9.28125C67.1504 8.85807 66.9941 8.48372 66.7793 8.1582C66.571 7.83268 66.304 7.57878 65.9785 7.39648C65.6595 7.20768 65.2786 7.11328 64.8359 7.11328C64.3932 7.11328 64.0091 7.20768 63.6836 7.39648C63.3646 7.57878 63.1009 7.83268 62.8926 8.1582C62.6842 8.48372 62.5312 8.85807 62.4336 9.28125C62.3359 9.69792 62.2871 10.1406 62.2871 10.6094Z" fill="#C24225" />
                    <path d="M49.1426 1H51.4961V13.7441L51.2715 16H49.1426V1ZM58.3906 10.6191V10.8242C58.3906 11.6055 58.3027 12.3249 58.127 12.9824C57.9577 13.6335 57.6973 14.1999 57.3457 14.6816C57.0007 15.1634 56.571 15.5378 56.0566 15.8047C55.5488 16.0651 54.9596 16.1953 54.2891 16.1953C53.6315 16.1953 53.0586 16.0716 52.5703 15.8242C52.082 15.5768 51.6719 15.2253 51.3398 14.7695C51.0143 14.3138 50.7507 13.7702 50.5488 13.1387C50.347 12.5072 50.2038 11.8105 50.1191 11.0488V10.3945C50.2038 9.6263 50.347 8.92969 50.5488 8.30469C50.7507 7.67318 51.0143 7.12956 51.3398 6.67383C51.6719 6.21159 52.0788 5.85677 52.5605 5.60938C53.0488 5.36198 53.6185 5.23828 54.2695 5.23828C54.9466 5.23828 55.5423 5.36849 56.0566 5.62891C56.5775 5.88932 57.0104 6.26042 57.3555 6.74219C57.7005 7.21745 57.9577 7.78385 58.127 8.44141C58.3027 9.09896 58.3906 9.82487 58.3906 10.6191ZM56.0371 10.8242V10.6191C56.0371 10.1439 55.998 9.69792 55.9199 9.28125C55.8418 8.85807 55.7116 8.48698 55.5293 8.16797C55.3535 7.84896 55.1126 7.59831 54.8066 7.41602C54.5072 7.22721 54.1328 7.13281 53.6836 7.13281C53.2669 7.13281 52.9089 7.20443 52.6094 7.34766C52.3099 7.49089 52.0592 7.6862 51.8574 7.93359C51.6556 8.18099 51.4961 8.46745 51.3789 8.79297C51.2682 9.11849 51.1934 9.47005 51.1543 9.84766V11.6152C51.2129 12.1035 51.3366 12.5527 51.5254 12.9629C51.7207 13.3665 51.9941 13.6921 52.3457 13.9395C52.6973 14.1803 53.1497 14.3008 53.7031 14.3008C54.1393 14.3008 54.5072 14.2129 54.8066 14.0371C55.1061 13.8613 55.3438 13.6172 55.5195 13.3047C55.7018 12.9857 55.832 12.6146 55.9102 12.1914C55.9948 11.7682 56.0371 11.3125 56.0371 10.8242Z" fill="#C24225" />
                    <path d="M40.334 7.68945V16H37.9805V5.43359H40.1973L40.334 7.68945ZM39.9141 10.3262L39.1523 10.3164C39.1589 9.56771 39.263 8.88086 39.4648 8.25586C39.6732 7.63086 39.9596 7.09375 40.3242 6.64453C40.6953 6.19531 41.138 5.85026 41.6523 5.60938C42.1667 5.36198 42.7396 5.23828 43.3711 5.23828C43.8789 5.23828 44.3379 5.3099 44.748 5.45312C45.1647 5.58984 45.5195 5.81445 45.8125 6.12695C46.112 6.43945 46.3398 6.84635 46.4961 7.34766C46.6523 7.84245 46.7305 8.45117 46.7305 9.17383V16H44.3672V9.16406C44.3672 8.65625 44.2923 8.25586 44.1426 7.96289C43.9993 7.66341 43.7878 7.45182 43.5078 7.32812C43.2344 7.19792 42.8926 7.13281 42.4824 7.13281C42.0788 7.13281 41.7174 7.21745 41.3984 7.38672C41.0794 7.55599 40.8092 7.78711 40.5879 8.08008C40.373 8.37305 40.207 8.71159 40.0898 9.0957C39.9727 9.47982 39.9141 9.88997 39.9141 10.3262Z" fill="#C24225" />
                    <path d="M35.7832 8.5V9.28125C35.7832 10.3555 35.6432 11.319 35.3633 12.1719C35.0833 13.0247 34.6829 13.7507 34.1621 14.3496C33.6478 14.9486 33.0293 15.4076 32.3066 15.7266C31.584 16.0391 30.7832 16.1953 29.9043 16.1953C29.0319 16.1953 28.2344 16.0391 27.5117 15.7266C26.7956 15.4076 26.1738 14.9486 25.6465 14.3496C25.1191 13.7507 24.709 13.0247 24.416 12.1719C24.1296 11.319 23.9863 10.3555 23.9863 9.28125V8.5C23.9863 7.42578 24.1296 6.46549 24.416 5.61914C24.7025 4.76628 25.1061 4.04036 25.627 3.44141C26.1543 2.83594 26.776 2.37695 27.4922 2.06445C28.2148 1.74544 29.0124 1.58594 29.8848 1.58594C30.7637 1.58594 31.5645 1.74544 32.2871 2.06445C33.0098 2.37695 33.6315 2.83594 34.1523 3.44141C34.6732 4.04036 35.0736 4.76628 35.3535 5.61914C35.64 6.46549 35.7832 7.42578 35.7832 8.5ZM33.332 9.28125V8.48047C33.332 7.6862 33.2539 6.98633 33.0977 6.38086C32.9479 5.76888 32.7233 5.25781 32.4238 4.84766C32.1309 4.43099 31.7695 4.11849 31.3398 3.91016C30.9102 3.69531 30.4251 3.58789 29.8848 3.58789C29.3444 3.58789 28.8626 3.69531 28.4395 3.91016C28.0163 4.11849 27.6549 4.43099 27.3555 4.84766C27.0625 5.25781 26.8379 5.76888 26.6816 6.38086C26.5254 6.98633 26.4473 7.6862 26.4473 8.48047V9.28125C26.4473 10.0755 26.5254 10.7786 26.6816 11.3906C26.8379 12.0026 27.0658 12.5202 27.3652 12.9434C27.6712 13.36 28.0358 13.6758 28.459 13.8906C28.8822 14.099 29.3639 14.2031 29.9043 14.2031C30.4512 14.2031 30.9362 14.099 31.3594 13.8906C31.7826 13.6758 32.1406 13.36 32.4336 12.9434C32.7266 12.5202 32.9479 12.0026 33.0977 11.3906C33.2539 10.7786 33.332 10.0755 33.332 9.28125Z" fill="#C24225" />
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M15.6522 2.34783H2.34783V15.6522H15.6522V2.34783ZM0 0V18H18V0H0Z" fill="#C24225" />
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M15.6522 2.34783H9.39134V8.6087H15.6522V2.34783ZM7.04352 0V10.9565H18V0H7.04352Z" fill="#C24225" />
                </svg>
            </section>
            <header class="o-section c-section--header">
                <div class="o-section__wrapper">
                    <div class="c-header">
                        <nav class="c-header_navigation">
                            <ul class="s-nav s-nav--steps">
                                <?php
                                $active = 'is-active';
                                foreach (Wizard::getSteps() as $step) {
                                    echo '<li class="' . $active . '"><span></span><a href="' . Wizard::stepLink($step['slug']) . '" title="' . $step['label'] . '">' . $step['label'] . '</a></li>';
                                    if ($currentStep['slug'] == $step['slug']) {
                                        $active = "";
                                    }
                                }
                                ?>
                            </ul>
                        </nav>
                    </div>
                </div>
            </header>
            <section class="o-section c-section--maincontent">
                <div class="o-section__wrapper o-section__wrapper--maincontent">
                    <div class="c-section__title u-border-b">
                        <h1 class=" u-m-0 u-text-orange"><?php echo isset($currentStep['label']) ? esc_attr($currentStep['label']) : ''; ?></h1>
                        <p class="u-m-0"><?php echo isset($currentStep['description']) ? esc_attr($currentStep['description']) : ''; ?></p>
                    </div>
                    <div class="c-form c-form--medium u-flex u-content-center">
                        <form method="post" action="<?php echo Wizard::formAction(); ?>">
                            <div>
                                <?php
                                wp_nonce_field();
                                foreach ($currentStep['fields'] as $field) {
                                    if ($field['type'] == 'text') {
                                        $fieldDesc = isset($field['description']) ? esc_attr($field['description']) : '';
                                        echo '<div class="c-form__fieldgroup u-mb-20 u-mt-20">
                                            ' . Wizard::renderField($field) . '    
                                            <p class="c-form__description"> ' . $fieldDesc . ' </p>
                                            </div>';
                                    } else {
                                        Wizard::renderField($field);
                                    }
                                }
                                ?>
                            </div>
                            <div class="c-form__footer u-flex-end">
                                <input class="c-btn c-btn--primary" type="submit" value="<?php echo isset($currentStep['next']) ? 'Continue' : 'Finish'; ?> " />
                            </div>
                        </form>

                    </div>
                </div>
            </section>
            <section class="c-section--nextstep u-text-center ">
                <div class="u-flex u-content-center">
                    <?php
                    echo    Wizard::renderPrevBtn();
                    echo    Wizard::renderExitBtn();
                    echo    Wizard::renderNextBtn();
                    ?>
                </div>
            </section>
        </div>
        <?php if (isset($config['js_url']) &&  $config['js_url'] != "") {
                echo '<script src="' . $config['js_url'] . '"></script>';
            } else {
                echo '<script src="' . plugin_dir_url(__FILE__) . '/assets/main.min.js"></script>';
            }
        ?>
    </body>
</div>

<style>
    .onboarding-wrap {
        width: 100%;
        min-height: 100%;
        position: fixed;
        z-index: 99999;
        left: 0px;
        top: 0;
        right: 0;
        bottom: 0;
        background: #ddd;
        margin: 0;
        overflow: auto;
    }

    .onboarding-form {
        width: 50%;
        margin: 0 auto;
    }

    .s-nav--steps {
        justify-content: center;
    }

    .wizard-btn {
        cursor: pointer;
        border: 0;
        padding: 5px 10px;
        margin: 0 10px;
    }

    .exit-btn {
        background: #c53200d9;
        color: #fff;
    }

    .u-mt-20 {
        margin-top: 20px;
    }

    .w-auto {
        width: auto !important;
    }
</style>