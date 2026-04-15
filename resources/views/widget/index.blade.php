<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mini CRM Widget</title>
    <style>
        :root {
            color-scheme: light;
            --bg: linear-gradient(145deg, #f5efe5 0%, #fef9f3 45%, #e8f3f1 100%);
            --surface: rgba(255, 255, 255, 0.86);
            --surface-strong: #ffffff;
            --text: #102432;
            --muted: #5e6f7b;
            --accent: #e85d3f;
            --accent-dark: #bf4127;
            --line: rgba(16, 36, 50, 0.1);
            --success-bg: #e8f8ee;
            --success-text: #18613a;
            --error-bg: #fff0ea;
            --error-text: #9a3412;
            --shadow: 0 22px 60px rgba(16, 36, 50, 0.14);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: Manrope, "Segoe UI", sans-serif;
            background: var(--bg);
            color: var(--text);
            display: grid;
            place-items: center;
            padding: 20px;
        }

        .widget-shell {
            position: relative;
            width: min(100%, 720px);
        }

        .widget-shell::before,
        .widget-shell::after {
            content: "";
            position: absolute;
            inset: auto;
            width: 180px;
            height: 180px;
            border-radius: 999px;
            filter: blur(18px);
            z-index: 0;
        }

        .widget-shell::before {
            top: -26px;
            right: -16px;
            background: rgba(232, 93, 63, 0.22);
        }

        .widget-shell::after {
            bottom: -18px;
            left: -12px;
            background: rgba(36, 123, 160, 0.18);
        }

        .widget-card {
            position: relative;
            z-index: 1;
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 28px;
            background: var(--surface);
            box-shadow: var(--shadow);
            backdrop-filter: blur(18px);
            overflow: hidden;
        }

        .widget-header {
            padding: 28px 28px 18px;
            background:
                radial-gradient(circle at top right, rgba(232, 93, 63, 0.16), transparent 42%),
                linear-gradient(180deg, rgba(255, 255, 255, 0.82), rgba(255, 255, 255, 0.66));
            border-bottom: 1px solid var(--line);
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 12px;
            border-radius: 999px;
            background: rgba(16, 36, 50, 0.06);
            color: var(--muted);
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        h1 {
            margin: 18px 0 10px;
            font-size: clamp(28px, 5vw, 40px);
            line-height: 1.05;
        }

        .lead {
            margin: 0;
            color: var(--muted);
            line-height: 1.7;
            max-width: 56ch;
        }

        .widget-body {
            padding: 24px 28px 30px;
        }

        .status-box {
            display: none;
            border-radius: 18px;
            padding: 14px 16px;
            margin-bottom: 18px;
            font-size: 14px;
            line-height: 1.6;
        }

        .status-box.is-visible {
            display: block;
        }

        .status-box.is-success {
            background: var(--success-bg);
            color: var(--success-text);
        }

        .status-box.is-error {
            background: var(--error-bg);
            color: var(--error-text);
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
        }

        .field {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .field.is-full {
            grid-column: 1 / -1;
        }

        label {
            font-size: 14px;
            font-weight: 700;
        }

        input,
        textarea {
            width: 100%;
            border: 1px solid var(--line);
            border-radius: 18px;
            padding: 14px 16px;
            background: var(--surface-strong);
            color: var(--text);
            font: inherit;
            transition: border-color 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease;
        }

        input:focus,
        textarea:focus {
            outline: none;
            border-color: rgba(232, 93, 63, 0.7);
            box-shadow: 0 0 0 4px rgba(232, 93, 63, 0.12);
            transform: translateY(-1px);
        }

        textarea {
            min-height: 140px;
            resize: vertical;
        }

        .file-input {
            position: relative;
            overflow: hidden;
            border: 1px dashed rgba(16, 36, 50, 0.22);
            border-radius: 22px;
            padding: 18px;
            background: rgba(255, 255, 255, 0.7);
        }

        .file-input input {
            border: none;
            padding: 0;
            background: transparent;
            box-shadow: none;
        }

        .file-caption {
            margin: 0 0 12px;
            color: var(--muted);
            font-size: 14px;
            line-height: 1.6;
        }

        .field-error {
            min-height: 20px;
            color: var(--error-text);
            font-size: 13px;
        }

        .actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-top: 10px;
        }

        .hint {
            color: var(--muted);
            font-size: 13px;
            line-height: 1.6;
        }

        .submit-button {
            appearance: none;
            border: none;
            border-radius: 999px;
            padding: 15px 24px;
            background: linear-gradient(135deg, var(--accent), #f08a49);
            color: #fff;
            font: inherit;
            font-weight: 800;
            cursor: pointer;
            box-shadow: 0 14px 28px rgba(232, 93, 63, 0.22);
            transition: transform 0.2s ease, box-shadow 0.2s ease, opacity 0.2s ease;
        }

        .submit-button:hover {
            transform: translateY(-1px);
            box-shadow: 0 18px 36px rgba(232, 93, 63, 0.28);
        }

        .submit-button:disabled {
            cursor: wait;
            opacity: 0.7;
            transform: none;
        }

        @media (max-width: 640px) {
            body {
                padding: 14px;
            }

            .widget-header,
            .widget-body {
                padding-inline: 18px;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .actions {
                flex-direction: column;
                align-items: stretch;
            }

            .submit-button {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="widget-shell">
        <section class="widget-card" aria-labelledby="widget-title">
            <header class="widget-header">
                <span class="eyebrow">Feedback Widget</span>
                <h1 id="widget-title">Залиште заявку для менеджера</h1>
                <p class="lead">
                    Заповніть коротку форму, і менеджер отримає звернення в CRM одразу після відправлення.
                </p>
            </header>

            <div class="widget-body">
                <div id="statusBox" class="status-box" role="status" aria-live="polite"></div>

                <form id="ticketForm" novalidate>
                    <div class="form-grid">
                        <div class="field">
                            <label for="name">Ім’я</label>
                            <input id="name" name="name" type="text" autocomplete="name" placeholder="Наприклад, Ірина" required>
                            <div class="field-error" data-error-for="name"></div>
                        </div>

                        <div class="field">
                            <label for="phone">Телефон</label>
                            <input id="phone" name="phone" type="tel" autocomplete="tel" placeholder="+380501234567" required>
                            <div class="field-error" data-error-for="phone"></div>
                        </div>

                        <div class="field">
                            <label for="email">Email</label>
                            <input id="email" name="email" type="email" autocomplete="email" placeholder="name@example.com" required>
                            <div class="field-error" data-error-for="email"></div>
                        </div>

                        <div class="field">
                            <label for="subject">Тема</label>
                            <input id="subject" name="subject" type="text" placeholder="Потрібна консультація" required>
                            <div class="field-error" data-error-for="subject"></div>
                        </div>

                        <div class="field is-full">
                            <label for="message">Текст повідомлення</label>
                            <textarea id="message" name="message" placeholder="Опишіть ваш запит" required></textarea>
                            <div class="field-error" data-error-for="message"></div>
                        </div>

                        <div class="field is-full">
                            <label for="attachment">Файл</label>
                            <div class="file-input">
                                <p class="file-caption">Можна прикріпити один файл до 10 МБ.</p>
                                <input id="attachment" name="attachment" type="file">
                            </div>
                            <div class="field-error" data-error-for="attachment"></div>
                        </div>
                    </div>

                    <div class="actions">
                        <p class="hint">Обмеження: одна заявка на добу з одного email або номера телефону.</p>
                        <button id="submitButton" class="submit-button" type="submit">Надіслати заявку</button>
                    </div>
                </form>
            </div>
        </section>
    </div>

    <script>
        const form = document.getElementById('ticketForm');
        const statusBox = document.getElementById('statusBox');
        const submitButton = document.getElementById('submitButton');
        const endpoint = @json(route('api.tickets.store'));

        const clearErrors = () => {
            document.querySelectorAll('[data-error-for]').forEach((element) => {
                element.textContent = '';
            });
        };

        const setStatus = (message, type) => {
            statusBox.textContent = message;
            statusBox.className = `status-box is-visible is-${type}`;
        };

        form.addEventListener('submit', async (event) => {
            event.preventDefault();

            clearErrors();
            setStatus('', 'success');
            statusBox.classList.remove('is-visible');
            submitButton.disabled = true;
            submitButton.textContent = 'Відправляємо...';

            try {
                const response = await fetch(endpoint, {
                    method: 'POST',
                    headers: {
                        Accept: 'application/json',
                    },
                    body: new FormData(form),
                });

                const payload = await response.json().catch(() => ({}));

                if (!response.ok) {
                    if (response.status === 422 && payload.errors) {
                        Object.entries(payload.errors).forEach(([field, messages]) => {
                            const errorNode = document.querySelector(`[data-error-for="${field}"]`);

                            if (errorNode) {
                                errorNode.textContent = Array.isArray(messages) ? messages[0] : messages;
                            }
                        });

                        setStatus('Перевірте форму і виправте помилки перед повторною відправкою.', 'error');
                        return;
                    }

                    throw new Error(payload.message || 'Не вдалося відправити заявку.');
                }

                form.reset();
                setStatus('Заявку успішно надіслано. Менеджер зв’яжеться з вами після обробки.', 'success');
            } catch (error) {
                setStatus(error.message || 'Сталася помилка під час відправлення заявки.', 'error');
            } finally {
                submitButton.disabled = false;
                submitButton.textContent = 'Надіслати заявку';
            }
        });
    </script>
</body>
</html>
