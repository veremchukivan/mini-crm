<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Вхід | Mini CRM</title>
    <style>
        :root {
            --bg: radial-gradient(circle at top, #fff4e9 0%, #f8ede3 36%, #edf4f2 100%);
            --panel: rgba(255, 255, 255, 0.86);
            --text: #162734;
            --muted: #627583;
            --line: rgba(22, 39, 52, 0.12);
            --accent: #c45534;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 20px;
            font-family: Manrope, "Segoe UI", sans-serif;
            background: var(--bg);
            color: var(--text);
        }

        .card {
            width: min(100%, 460px);
            padding: 30px;
            border-radius: 28px;
            background: var(--panel);
            border: 1px solid rgba(255, 255, 255, 0.7);
            box-shadow: 0 26px 64px rgba(22, 39, 52, 0.12);
            backdrop-filter: blur(14px);
        }

        .eyebrow {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 999px;
            background: rgba(22, 39, 52, 0.06);
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-size: 12px;
            font-weight: 800;
        }

        h1 {
            margin: 18px 0 10px;
            font-size: 34px;
            line-height: 1.05;
        }

        p {
            margin: 0 0 22px;
            color: var(--muted);
            line-height: 1.7;
        }

        form {
            display: grid;
            gap: 16px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 700;
            font-size: 14px;
        }

        input {
            width: 100%;
            padding: 14px 16px;
            border-radius: 18px;
            border: 1px solid var(--line);
            background: #fff;
            color: var(--text);
            font: inherit;
        }

        .error {
            color: #a03c1e;
            font-size: 13px;
            margin-top: 8px;
        }

        .checkbox {
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--muted);
            font-size: 14px;
        }

        .checkbox input {
            width: auto;
            margin: 0;
        }

        button {
            border: none;
            border-radius: 999px;
            padding: 14px 20px;
            background: linear-gradient(135deg, var(--accent), #e48348);
            color: #fff;
            font: inherit;
            font-weight: 800;
            cursor: pointer;
        }

        .credentials {
            margin-top: 20px;
            padding: 16px;
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.68);
            color: var(--muted);
            font-size: 14px;
            line-height: 1.7;
        }
    </style>
</head>
<body>
    <section class="card">
        <span class="eyebrow">Manager Access</span>
        <h1>Вхід до адмін-панелі</h1>
        <p>Авторизуйтесь під тестовим менеджером або власним користувачем з роллю `manager` чи `admin`.</p>

        <form method="post" action="{{ route('login.store') }}">
            @csrf

            <div>
                <label for="email">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label for="password">Пароль</label>
                <input id="password" name="password" type="password" required>
                @error('password')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <label class="checkbox">
                <input type="checkbox" name="remember" value="1">
                Запам’ятати мене
            </label>

            <button type="submit">Увійти</button>
        </form>

        <div class="credentials">
            Тестовий менеджер: `manager@mini-crm.test`<br>
            Пароль: `password`
        </div>
    </section>
</body>
</html>
