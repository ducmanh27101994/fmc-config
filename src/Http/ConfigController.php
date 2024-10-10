<?php

namespace Fmcpay\Config\Http;

use Fmcpay\Config\Model\GeneralConfiguration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ConfigController
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $input = $request->all();
        $validate = Validator::make($input, [
            'per_page' => 'integer|min:1',
        ], [
            'per_page.integer' => 'per_page phải là số nguyên ',
            'per_page.min' => 'per_page phải lớn hơn 1 ',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'message' => $validate->errors(),
                'status' => 400,
            ]);
        }

        $configurations = GeneralConfiguration::paginate($perPage);

        return response()->json([
            'message' => 'Success',
            'status' => 200,
            'data' => $configurations ?? [],
            'paginate' => [
                'current_page' => $configurations->currentPage(),
                'total' => $configurations->total(),
                'per_page' => $configurations->perPage(),
                'last_page' => $configurations->lastPage(),
            ],
        ]);
    }

    public function createConfig(Request $request)
    {
        $input = $request->all();
        $validate = Validator::make($input, [
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'status' => 'required|integer|in:0,1',
            'configuration_type' => 'required|string|regex:/^[a-zA-Z_]+$/',
        ], [
            'start_time.required' => 'Thời gian bắt đầu là bắt buộc.',
            'start_time.date' => 'Thời gian bắt đầu không hợp lệ.',
            'end_time.required' => 'Thời gian kết thúc là bắt buộc.',
            'end_time.date' => 'Thời gian kết thúc không hợp lệ.',
            'end_time.after' => 'Thời gian kết thúc phải lớn hơn thời gian bắt đầu.',
            'status.required' => 'Trạng thái là bắt buộc.',
            'status.integer' => 'Trạng thái phải là một số nguyên.',
            'status.in' => 'Trạng thái phải là 0 hoặc 1.',
            'configuration_type.required' => 'Loại cấu hình là bắt buộc.',
            'configuration_type.string' => 'Loại cấu hình phải là một chuỗi.',
            'configuration_type.regex' => 'Loại cấu hình chỉ được chứa chữ cái và dấu gạch dưới.',
        ]);
        if ($validate->fails()) {
            return response()->json([
                'message' => $validate->errors(),
                'status' => 400,
            ]);
        }

        GeneralConfiguration::create($request->all());

        return response()->json([
            'message' => 'Tạo config thành công',
            'status' => 200,
        ]);
    }

    public function destroy(Request $request)
    {
        $config_id = $request->input('id');
        if (!$config_id) {
            return response()->json([
                'message' => 'Chưa có id config',
                'status' => 400,
            ]);
        }

        $configuration = GeneralConfiguration::findOrFail($config_id);
        $configuration->delete();

        return response()->json([
            'message' => 'Xóa config thành công',
            'status' => 200,
        ]);
    }

    public function update(Request $request)
    {
        $input = $request->all();
        $validate = Validator::make($input, [
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'status' => 'required|integer|in:0,1',
            'configuration_type' => 'required|string|regex:/^[a-zA-Z_]+$/',
        ], [
            'start_time.required' => 'Thời gian bắt đầu là bắt buộc.',
            'start_time.date' => 'Thời gian bắt đầu không hợp lệ.',
            'end_time.required' => 'Thời gian kết thúc là bắt buộc.',
            'end_time.date' => 'Thời gian kết thúc không hợp lệ.',
            'end_time.after' => 'Thời gian kết thúc phải lớn hơn thời gian bắt đầu.',
            'status.required' => 'Trạng thái là bắt buộc.',
            'status.integer' => 'Trạng thái phải là một số nguyên.',
            'status.in' => 'Trạng thái phải là 0 hoặc 1.',
            'configuration_type.required' => 'Loại cấu hình là bắt buộc.',
            'configuration_type.string' => 'Loại cấu hình phải là một chuỗi.',
            'configuration_type.regex' => 'Loại cấu hình chỉ được chứa chữ cái và dấu gạch dưới.',
        ]);
        if ($validate->fails()) {
            return response()->json([
                'message' => $validate->errors(),
                'status' => 400,
            ]);
        }

        $config = GeneralConfiguration::find($input['id']);
        if (!$config) {
            return response()->json([
                'message' => 'Config không tồn tại ',
                'status' => 400,
            ]);
        }

        $config->start_time = $input['start_time'];
        $config->end_time = $input['end_time'];
        $config->status = $input['status'];
        $config->configuration_type = $input['configuration_type'];
        $config->save();

        return response()->json([
            'message' => 'Cập nhật config thành công',
            'status' => 200,
        ]);
    }

    public function toggleStatus(Request $request)
    {
        $id = $request->input('id');
        if (!$id) {
            return response()->json([
                'message' => 'Chưa có id config',
                'status' => 400,
            ]);
        }

        $configuration = GeneralConfiguration::find($id);
        if (!$configuration) {
            return response()->json([
                'message' => 'Cấu hình không tồn tại.',
                'status' => 400,
            ]);
        }

        if ($configuration->status == 1) {
            $configuration->deactivate();
            return response()->json([
                'message' => 'Cấu hình đã bị hủy kích hoạt.',
                'status' => 200,
            ]);
        } else {
            $configuration->activate();
            return response()->json([
                'message' => 'Cấu hình đã được kích hoạt.',
                'status' => 200,
            ]);
        }
    }




}
