/* Library Imports */
//React
import React, { useEffect, useState } from "react";

/* Stylesheet Imports */

/* Image Imports */

/* Component Imports */

/* Module Imports */
import { createShipment } from "@/modules/api/shipments";

/* Component Interfaces */
interface Props {
  modeSetter: (string: string) => void;
  refetch: any;
  targeter: (any: any) => void;
}

/* Component */
const ShipmentForm: React.FC<Props> = ({ modeSetter, refetch, targeter }) => {
  /* State Variables */
  const [compMode, setCompMode] = useState<string>("create");
  const [name, setName] = useState<string>("");
  const [date, setDate] = useState<string>("");
  const [summary, setSummary] = useState<string>("");
  const [lastCreatedID, setLastCreatedID] = useState<number | null>(null);

  /* End State Variables */

  /* Render Variables */
  /* End Render Variables */

  /* Functions */
  function submitNewShipment(name: string, date: string, summary: string) {
    createShipment(name, date, summary).then((res) => {
      if (res && res.message) {
        setLastCreatedID(res.shipment.id);
        refetch();
        setCompMode("view");
      }
    });
  }

  function cancelAddShipment() {
    setName("");
    setDate("");
    setSummary("");
    modeSetter("manifest");
  }

  /* End Functions */

  /* Effects */

  /* End Effects */

  /* Component Return Statement */
  if (compMode === "view") {
    return (
      <div className="ShipmentForm w-full flex flex-col my-5">
        <h1 className="text-center text-4xl">New Shipment Added!</h1>
        <div className="flex flex-col my-2 w-[40%] mx-auto">
          <h2>Name: {name}</h2>
          <h2>Date: {date}</h2>
          <h2>Summary: {summary}</h2>
        </div>
        <button
          className="bg-blue-500 hover:bg-blue-700 py-3 px-5 w-[40%] mx-auto block my-4"
          onClick={(e) => {
            e.preventDefault();
            setCompMode("create");
            setDate("");
            setName("");
            setSummary("");
          }}
        >
          Add Another Shipment
        </button>
        <button
          className="bg-yellow-500 hover:bg-yellow-700 py-3 px-5 w-[40%] mx-auto block"
          onClick={(e) => {
            e.preventDefault();
            targeter(lastCreatedID);
          }}
        >
          Add Contents To Shipment
        </button>
        <button
          className="bg-green-500 hover:bg-green-700 py-3 px-5 w-[40%] mx-auto block my-4"
          onClick={(e) => {
            e.preventDefault();
            modeSetter("manifest");
            setCompMode("view");
          }}
        >
          View All Shipments
        </button>
      </div>
    );
  }
  return (
    <div className="ShipmentForm w-full flex flex-col my-5">
      <h1 className="text-center text-4xl">Add A Shipment</h1>
      <form className="w-[50%] mx-auto">
        <div className="flex flex-col my-2">
          <label htmlFor="name">Name:</label>
          <input
            className="border border-black"
            type="text"
            id="name"
            name="name"
            onChange={(e) => {
              setName(e.target.value);
            }}
          />
        </div>
        <div className="flex flex-col my-2">
          <label htmlFor="date">Date:</label>
          <input
            className="border border-black"
            type="date"
            id="date"
            name="date"
            onChange={(e) => {
              setDate(e.target.value);
            }}
          />
        </div>
        <div className="flex flex-col">
          <label htmlFor="summary">Summary</label>
          <input
            className="border border-black"
            type="textarea"
            name="summary"
            id="summary"
            onChange={(e) => {
              setSummary(e.target.value);
            }}
          />
        </div>
        <div className="flex flex-row">
          <button
            onClick={(e) => {
              e.preventDefault();
              submitNewShipment(name, date, summary);
            }}
            className="bg-blue-500 hover:bg-blue-700 py-3 px-5 w-[40%] mx-auto block my-4"
            type="submit"
          >
            Add Shipment
          </button>
          <button
            className="bg-red-500 hover:bg-red-700 py-3 px-5 w-[40%] mx-auto block my-4"
            type="submit"
            onClick={(e) => {
              e.preventDefault();
              cancelAddShipment();
            }}
          >
            Cancel
          </button>
        </div>
      </form>
    </div>
  );
};

/* Export Statement */
export default ShipmentForm;
