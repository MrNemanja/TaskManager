import './TaskList.css'

function TaskList({tasks, onChange}){

   const handleDelete = async (id) => {
    await fetch("http://localhost:4000/tasks.php", {
      method: "DELETE",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ id }),
    });
    onChange();
  };

  const handleDone = async (id) => {
    await fetch("http://localhost:4000/tasks.php", {
      method: "PATCH",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ id }),
    });
    onChange();
  }


    return(
      <div className="space-y-3">
      {tasks.length === 0 ? (
        <p className="text-gray-400 text-center italic">No tasks yet</p>
      ) : (
        tasks.map((task) => (
          <div
            key={task.id}
            className={`bg-gray-800 p-4 rounded-xl shadow-md flex justify-between items-center transition-all ${
              task.done ? "opacity-60" : ""
            }`}
          >
            <div>
              <h2
                className={`text-lg font-semibold ${
                  task.done ? "line-through text-gray-400" : "text-gray-100"
                }`}
              >
                {task.title}
              </h2>
              {task.description && (
                <p className="text-gray-400 text-sm mt-1">
                  {task.description}
                </p>
              )}
            </div>
            <div className="flex gap-2">
              {!task.done && (
                <button
                  onClick={() => handleDone(task.id)}
                  className="px-3 py-1 rounded-md bg-green-600 hover:bg-green-700 transition-all text-sm"
                >
                  Done
                </button>
              )}
              <button
                onClick={() => handleDelete(task.id)}
                className="px-3 py-1 rounded-md bg-red-600 hover:bg-red-700 transition-all text-sm"
              >
                Delete
              </button>
            </div>
          </div>
        ))
      )}
    </div>
    );
}

export default TaskList;