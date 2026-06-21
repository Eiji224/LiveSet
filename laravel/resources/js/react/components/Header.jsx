export default function Header({ isLiveTraining, title, setTitle, description, setDescription, isPrivate, setIsPrivate }) {
    return (
    <div className="flex flex-col gap-3 p-5">
        <input
            type="text"
            value={title}
            disabled={isLiveTraining}
            onChange={e => setTitle(e.target.value)}
            placeholder="Введите название тренировки..."
            className="w-full border rounded-lg text-center text-2xl"
        />

        <input
            type="text"
            value={description}
            disabled={isLiveTraining}
            onChange={e => setDescription(e.target.value)}
            placeholder="Введите описание тренировки..."
            className="w-full border rounded-lg text-center text-2xl"
        />

        <div>
            <label>
                <input
                    type="radio"
                    name="isPrivate"
                    checked={!isPrivate}
                    disabled={isLiveTraining}
                    onChange={() => setIsPrivate(false)}
                />
                Публичная тренировки
            </label>
            <label>
                <input
                    type="radio"
                    name="isPrivate"
                    checked ={isPrivate}
                    disabled={isLiveTraining}
                    onChange={() => setIsPrivate(true)}
                />
                Приватная тренировки
            </label>
        </div>
    </div>
    );
}
