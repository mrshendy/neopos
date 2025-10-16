(function () {
    "use strict";

    let quickCheckinActive = false;
    let barcodeBuffer = "";
    let barcodeTimer = null;
    let barcodeStartTime = 0;
    let refocusInterval = null;
    let observer = null;
    let inputEventHandler = null;
    let keydownHandler = null;

    function cleanup() {
        document.removeEventListener("keydown", barcodeListener);
        if (refocusInterval) {
            clearInterval(refocusInterval);
            refocusInterval = null;
        }

        const input = document.getElementById("member_code_input");
        if (input && inputEventHandler) {
            input.removeEventListener("input", inputEventHandler);
        }
        if (keydownHandler) {
            document.removeEventListener("keydown", keydownHandler);
        }

        quickCheckinActive = false;
        barcodeBuffer = "";
        console.log("🧹 Quick Check-in cleaned.");
    }

    function initQuickCheckin() {
        const input = document.getElementById("member_code_input");
        if (!input) {
            console.log("⚠️ No #member_code_input found.");
            return;
        }

        if (quickCheckinActive) {
            console.log("⚡ Already active.");
            return;
        }

        quickCheckinActive = true;
        console.log("✅ Quick Check-in Initialized.");

        setTimeout(() => {
            input.focus();
        }, 100);

        refocusInterval = setInterval(() => {
            const active = document.activeElement;
            const modal = document.querySelector(".modal.show");
            if (
                !modal &&
                active !== input &&
                document.getElementById("member_code_input")
            ) {
                input.focus();
            }
        }, 3000);

        let inputTimeout = null;
        inputEventHandler = function () {
            this.value = this.value.replace(/\s+/g, "");
            if (this.value.length >= 8) {
                clearTimeout(inputTimeout);
                inputTimeout = setTimeout(() => {
                    sendCodeToLivewire(this.value.trim(), input);
                }, 150);
            }
        };
        input.addEventListener("input", inputEventHandler);

        keydownHandler = function (e) {
            const currentInput = document.getElementById("member_code_input");
            if (!currentInput) return;

            if (e.key === "Escape") {
                e.preventDefault();
                currentInput.value = "";
                window.Livewire?.emit("clearMember");
            }

            if (e.key === "F1") {
                e.preventDefault();
                currentInput.focus();
                currentInput.select();
            }

            if (e.key === "Enter" && document.activeElement === currentInput) {
                e.preventDefault();
                if (currentInput.value.trim().length >= 5) {
                    sendCodeToLivewire(currentInput.value.trim(), currentInput);
                }
            }
        };
        document.addEventListener("keydown", keydownHandler);
        document.addEventListener("keydown", barcodeListener);
    }

    function barcodeListener(e) {
        const input = document.getElementById("member_code_input");
        if (!input) return;

        if (e.ctrlKey || e.altKey || e.metaKey) return;
        if (["Shift", "CapsLock", "Tab"].includes(e.key)) return;

        const now = Date.now();
        if (now - barcodeStartTime > 50) barcodeBuffer = "";
        barcodeStartTime = now;

        if (e.key.length === 1) barcodeBuffer += e.key;

        if (e.key === "Enter") {
            e.preventDefault();
            if (barcodeBuffer.length >= 5) {
                const code = barcodeBuffer.trim();
                console.log("📦 Barcode detected:", code);
                input.value = code;
                input.focus();
                sendCodeToLivewire(code, input);
            }
            barcodeBuffer = "";
            return;
        }

        clearTimeout(barcodeTimer);
        barcodeTimer = setTimeout(() => {
            barcodeBuffer = "";
        }, 200);
    }

    function sendCodeToLivewire(code, input) {
        const component = window.Livewire?.find(
            input.closest("[wire\\:id]")?.getAttribute("wire:id")
        );
        if (component) {
            component.set("member_code", code);
            setTimeout(() => component.call("searchMember"), 100);
        }
    }

    // 🔧 الوظيفة العامة لإعادة التهيئة
    window.reinitQuickCheckin = function () {
        console.log("🔧 Manual reinit triggered");
        cleanup();
        setTimeout(initQuickCheckin, 100);
    };

    // التهيئة الأولى
    if (typeof Livewire !== "undefined") {
        document.addEventListener("livewire:load", () => {
            console.log("🌐 Livewire loaded");
            setTimeout(initQuickCheckin, 200);
        });
    } else {
        document.addEventListener("DOMContentLoaded", () => {
            console.log("🧩 DOM loaded");
            setTimeout(initQuickCheckin, 200);
        });
    }

    window.addEventListener("beforeunload", cleanup);
})();
