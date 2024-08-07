/* Library Imports */
//React
import React, { useState } from "react";

/* Stylesheet Imports */

/* Image Imports */
import { IoMdArrowRoundBack } from "react-icons/io";

/* Component Imports */
import AreYouSure from "../global/AreYouSure";
import SKUForm from "./SKUForm";

/* Module Imports */

/* Component Interfaces */
interface Props {
  data: any;
  manifest: any[];
  clearer: any;
  refetch: any;
  shipmentRefetch: any;
}

/* Component */
const ShipmentDetail: React.FC<Props> = ({
  data,
  manifest,
  clearer,
  refetch,
  shipmentRefetch,
}) => {
  /* State Variables */
  const [showSKUForm, setShowSKUForm] = useState(false);
  const [confirmDeletion, setConfirmDeletion] = useState(false);
  /* End State Variables */

  /* Render Variables */
  let renderedManifest: any = (
    <p className="text-xl">No manifest to display!</p>
  );
  /* End Render Variables */

  /* Render Logic */
  if (manifest && manifest.length > 0) {
    renderedManifest = manifest.map((item) => {
      return (
        <div className="border border-black p-2 flex flex-row justify-evenly">
          <h2 className="text-xl w-[30%] text-center">SKU: {item.sku}</h2>
          <p className="text-xl w-[30%] text-center">
            Quantity: {item.quantity}
          </p>
        </div>
      );
    });
  }
  /* End Render Logic */

  /* Functions */
  /* End Functions */

  /* Effects */
  /* End Effects */

  /* Component Return Statement */
  return (
    <div className="ShipmentDetail  w-full flex flex-col my-5">
      {showSKUForm && (
        <SKUForm
          closer={setShowSKUForm}
          shipmentID={data.id}
          refetch={refetch}
        />
      )}
      {confirmDeletion ? (
        <AreYouSure
          closer={setConfirmDeletion}
          shipmentID={data.id}
          clearer={clearer}
          shipmentRefetch={shipmentRefetch}
        />
      ) : null}
      <IoMdArrowRoundBack
        className="absolute w-12 h-12 text-black top-[50px] left-[200px] cursor-pointer hover:text-red-500"
        onClick={() => clearer()}
      />
      <h1 className="text-center text-4xl">Shipment Details</h1>
      <div className="w-[50%] min-h-[250px] border border-black mx-auto my-5">
        <h2 className="text-center text-2xl my-3">Shipment ID: {data.id}</h2>
        <h2 className="text-center text-2xl my-3">
          Shipment Name: {data.name}
        </h2>
        <h2 className="text-center text-2xl my-3">
          Shipment Date: {data.date}
        </h2>
        <div className="flex flex-col">
          <h2 className="text-center text-2xl my-3">Summary:</h2>
          <p className="text-center text-xl">{data.summary}</p>
        </div>
      </div>
      <div className="shipmentActions w-full flex flex-row justify-center">
        <button
          className="bg-green-500 py-4 px-8 mx-2 hover:bg-green-900"
          onClick={(e) => setShowSKUForm(true)}
        >
          ADD SKUS TO SHIPMENT
        </button>
        <button
          className="bg-red-500 py-4 px-8 mx-2 hover:bg-red-900"
          onClick={(e) => setConfirmDeletion(true)}
        >
          DELETE SHIPMENT
        </button>
      </div>
      <div className="w-[75%] my-5 mx-auto grid grid-cols-1 gap-6">
        {renderedManifest}
      </div>
    </div>
  );
};

/* Export Statement */
export default ShipmentDetail;
